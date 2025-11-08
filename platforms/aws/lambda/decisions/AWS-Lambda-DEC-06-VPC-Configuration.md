# AWS-Lambda-DEC-06-VPC-Configuration.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** VPC configuration decision for Lambda functions  
**Category:** AWS Platform - Lambda  
**Type:** Decision

---

## DECISION

**DEC-06:** Use VPC configuration only when Lambda needs to access private resources (RDS, ElastiCache, private APIs). For public internet access or AWS services, avoid VPC to minimize cold start time and complexity.

---

## CONTEXT

Lambda functions can run in two modes:
1. **No VPC (default):** Public internet access, AWS service access via IAM
2. **VPC-connected:** Access to private VPC resources (RDS, ElastiCache, etc.)

**Challenge:** VPC configuration adds significant cold start latency (was 5-15 seconds, improved to 1-2 seconds with Hyperplane ENI) and operational complexity. Need clear criteria for when VPC is necessary.

---

## PROBLEM

**When Do We Need VPC?**

Lambda functions may need to connect to:
- Private RDS databases
- ElastiCache clusters
- Private APIs (not internet-accessible)
- EC2 instances
- On-premises resources via VPN/Direct Connect

**Trade-offs:**
- **VPC:** Access to private resources, but slower cold starts, NAT Gateway costs
- **No VPC:** Fast cold starts, simpler, but no private resource access

---

## OPTIONS CONSIDERED

### Option 1: Always Use VPC
**Pros:**
- Consistent networking model
- Can access any resource
- Security isolation

**Cons:**
- 1-2 second cold start penalty (every cold start)
- NAT Gateway costs ($0.045/hour + data transfer)
- ENI management complexity
- Slower development cycle

**Verdict:** ❌ Rejected - Unnecessary overhead for most functions

---

### Option 2: Never Use VPC
**Pros:**
- Fast cold starts (< 1 second)
- Simpler configuration
- Lower costs (no NAT Gateway)
- Direct AWS service access

**Cons:**
- Can't access private RDS/ElastiCache
- Can't access private APIs
- Limited security isolation

**Verdict:** ❌ Rejected - Can't access private resources when needed

---

### Option 3: VPC Only When Required (SELECTED)
**Pros:**
- VPC used only for functions needing private resources
- Most functions avoid VPC overhead
- Clear decision criteria
- Cost optimized

**Cons:**
- Need to categorize functions
- Some duplication if public/private versions needed

**Verdict:** ✅ Selected - Best balance

---

## DECISION CRITERIA

### Use VPC When:
```
✅ Accessing private RDS database
✅ Accessing ElastiCache
✅ Accessing private API Gateway
✅ Accessing EC2 instances
✅ Accessing on-premises via VPN/Direct Connect
✅ Strict security isolation required (compliance)
```

### Don't Use VPC When:
```
❌ Only accessing AWS services (S3, DynamoDB, SQS, SNS)
❌ Only making public internet API calls
❌ No private resource dependencies
❌ Optimizing for lowest latency
❌ Minimizing cold start time
```

---

## IMPLEMENTATION

### VPC Configuration (When Required)

```yaml
# SAM template for VPC-connected Lambda

Resources:
  MyFunction:
    Type: AWS::Serverless::Function
    Properties:
      VpcConfig:
        SecurityGroupIds:
          - !Ref LambdaSecurityGroup
        SubnetIds:
          - !Ref PrivateSubnet1
          - !Ref PrivateSubnet2
      
      # CRITICAL: Need permissions to create ENI
      Policies:
        - VPCAccessPolicy: {}
  
  LambdaSecurityGroup:
    Type: AWS::EC2::SecurityGroup
    Properties:
      GroupDescription: Security group for Lambda function
      VpcId: !Ref VPC
      SecurityGroupEgress:
        # Allow outbound to RDS
        - IpProtocol: tcp
          FromPort: 3306
          ToPort: 3306
          DestinationSecurityGroupId: !Ref RDSSecurityGroup
        # Allow outbound to internet (if NAT Gateway exists)
        - IpProtocol: -1
          CidrIp: 0.0.0.0/0
```

**IAM Permissions Required:**
```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": [
        "ec2:CreateNetworkInterface",
        "ec2:DescribeNetworkInterfaces",
        "ec2:DeleteNetworkInterface",
        "ec2:AssignPrivateIpAddresses",
        "ec2:UnassignPrivateIpAddresses"
      ],
      "Resource": "*"
    }
  ]
}
```

---

### Internet Access from VPC Lambda

**Problem:** VPC Lambda functions can't access internet by default.

**Solutions:**

#### Option A: NAT Gateway (Expensive but Simple)
```yaml
# Public subnet with NAT Gateway
NATGateway:
  Type: AWS::EC2::NatGateway
  Properties:
    SubnetId: !Ref PublicSubnet
    AllocationId: !GetAtt EIP.AllocationId

# Route table for private subnets
PrivateRouteTable:
  Type: AWS::EC2::RouteTable
  Properties:
    VpcId: !Ref VPC

PrivateRoute:
  Type: AWS::EC2::Route
  Properties:
    RouteTableId: !Ref PrivateRouteTable
    DestinationCidrBlock: 0.0.0.0/0
    NatGatewayId: !Ref NATGateway
```

**Cost:** ~$35/month per NAT Gateway + data transfer costs

#### Option B: VPC Endpoints (Free for AWS Services)
```yaml
# S3 VPC endpoint (free)
S3Endpoint:
  Type: AWS::EC2::VPCEndpoint
  Properties:
    VpcId: !Ref VPC
    ServiceName: !Sub 'com.amazonaws.${AWS::Region}.s3'
    RouteTableIds:
      - !Ref PrivateRouteTable

# DynamoDB VPC endpoint (free)
DynamoDBEndpoint:
  Type: AWS::EC2::VPCEndpoint
  Properties:
    VpcId: !Ref VPC
    ServiceName: !Sub 'com.amazonaws.${AWS::Region}.dynamodb'
    RouteTableIds:
      - !Ref PrivateRouteTable

# Interface endpoint for other services ($0.01/hour)
SQSEndpoint:
  Type: AWS::EC2::VPCEndpoint
  Properties:
    VpcId: !Ref VPC
    ServiceName: !Sub 'com.amazonaws.${AWS::Region}.sqs'
    VpcEndpointType: Interface
    SubnetIds:
      - !Ref PrivateSubnet1
    SecurityGroupIds:
      - !Ref EndpointSecurityGroup
```

**Recommendation:** Use VPC endpoints for AWS services, only use NAT Gateway if public internet access required.

---

### Performance Optimization

**Cold Start Comparison:**

| Configuration | Cold Start | Cost/Month |
|--------------|------------|------------|
| No VPC | 800ms | $0 |
| VPC + NAT Gateway | 1,800ms | $35+ |
| VPC + VPC Endpoints | 1,600ms | $0-7 |

**Optimization:** Pre-warm critical VPC functions with provisioned concurrency:
```yaml
ProvisionedConcurrencyConfig:
  ProvisionedConcurrentExecutions: 2  # Always 2 warm instances
```

---

## RATIONALE

### Why VPC Only When Required?

**Performance:**
- VPC adds 1-2s cold start overhead
- 90% of functions don't need private resource access
- VPC should be exception, not default

**Cost:**
- NAT Gateway: $35/month base + data transfer
- VPC endpoints: Free (gateway) or $7/month (interface)
- Provisioned concurrency: $0.015/hour per GB-second

**Complexity:**
- Security groups
- Subnet configuration
- ENI limits (350 per region by default)
- Route table management
- NAT Gateway/VPC endpoint setup

**Security:**
- VPC provides isolation, but so does IAM
- Most AWS services accessed via IAM, not VPC
- VPC adds attack surface (ENI management)

---

## ALTERNATIVES CONSIDERED

### AWS PrivateLink
**Use Case:** Access private API Gateway or custom services  
**Trade-off:** Interface endpoint cost ($0.01/hour)  
**When:** Need private API access without NAT Gateway

### Public RDS with IAM Auth
**Use Case:** RDS doesn't need to be in private subnet  
**Trade-off:** Public exposure (mitigated with IAM auth + security groups)  
**When:** Want to avoid VPC but use RDS

### Serverless Aurora Data API
**Use Case:** Access Aurora without VPC  
**Trade-off:** HTTP-based, not direct SQL connection  
**When:** Aurora database, want to avoid VPC

---

## IMPACT

### Example Application

**Function Mix:**
- 100 Lambda functions total
- 15 functions need RDS access (VPC required)
- 85 functions only use DynamoDB/S3 (no VPC)

**Before Decision (All Functions in VPC):**
- Cold start P99: 2,800ms
- NAT Gateway cost: $35/month
- Operational complexity: High (managing 100 VPC functions)

**After Decision (VPC Only for 15 Functions):**
- Cold start P99: 950ms (66% improvement for 85 functions)
- NAT Gateway cost: $35/month (same, but only 15 functions benefit)
- Operational complexity: Medium (managing 15 VPC + 85 non-VPC)
- **Net benefit:** 66% faster cold starts for 85% of functions

---

## MONITORING

### Key Metrics

**VPC Lambda:**
- ENI creation time (CloudWatch metric)
- Connection failures to private resources
- NAT Gateway data transfer costs
- ENI limit proximity (350/region)

**Non-VPC Lambda:**
- Internet connectivity issues
- AWS service access failures (IAM)

### Alarms

```yaml
ENICreationAlarm:
  Type: AWS::CloudWatch::Alarm
  Properties:
    MetricName: Duration  # High during cold start
    Threshold: 2000  # 2 seconds
    ComparisonOperator: GreaterThanThreshold
    
ENILimitAlarm:
  Type: AWS::CloudWatch::Alarm
  Properties:
    MetricName: NetworkInterfaceCount
    Threshold: 300  # 85% of 350 limit
    ComparisonOperator: GreaterThanThreshold
```

---

## LESSONS LEARNED

1. **VPC is expensive** (cold start time + NAT Gateway costs)
2. **Most functions don't need VPC** (IAM sufficient for AWS services)
3. **VPC endpoints save money** (vs NAT Gateway for AWS service access)
4. **Provisioned concurrency helps VPC cold starts** (but adds cost)
5. **ENI limits matter** (350/region, plan for scale)

---

## RELATED DECISIONS

- AWS-Lambda-DEC-01 (Single-threaded execution)
- AWS-Lambda-DEC-02 (Memory constraints)
- AWS-Lambda-DEC-05 (Cost optimization)
- AWS-Lambda-LESS-01 (Cold start impact)

---

## CROSS-REFERENCES

**From This File:**
- VPC best practices
- Network architecture patterns
- Cost optimization strategies

**To This File:**
- Lambda VPC configuration guide
- RDS access patterns
- ElastiCache integration

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial decision documenting VPC configuration strategy
- Clear criteria for VPC vs non-VPC
- Cost analysis (NAT Gateway vs VPC endpoints)
- Performance impact (cold start overhead)
- Implementation examples
- Monitoring and alarms

---

**END OF FILE**

**Category:** AWS Lambda Decisions  
**Priority:** High (affects performance and cost)  
**Impact:** 66% faster cold starts for non-VPC functions, clear decision criteria
