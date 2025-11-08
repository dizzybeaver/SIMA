# AWS-Lambda-AP-06-Not-Using-Layers.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Anti-pattern of not using Lambda Layers for shared code  
**Category:** AWS Platform - Lambda  
**Type:** Anti-Pattern

---

## ANTI-PATTERN

**AP-06:** Duplicating shared code, dependencies, or configuration across multiple Lambda functions instead of using Lambda Layers for code reuse.

---

## DESCRIPTION

Including the same libraries, shared utility code, or configuration files in every Lambda deployment package instead of extracting them into reusable Lambda Layers.

**Symptoms:**
- Same libraries bundled in 15+ function packages
- Identical utility modules copied across functions
- Deployment package size grows unnecessarily large
- Updates require redeploying all functions
- Inconsistent library versions across functions

---

## WHY IT'S WRONG

### 1. Wasted Storage and Deployment Time

**Problem:** Each function packages same dependencies independently.

**Impact:**
```
Without Layers:
- Function A: 45 MB (40 MB dependencies + 5 MB code)
- Function B: 45 MB (40 MB dependencies + 5 MB code)
- Function C: 45 MB (40 MB dependencies + 5 MB code)
- Total: 135 MB storage
- Deploy time: 15 seconds × 3 = 45 seconds

With Layers:
- Layer: 40 MB dependencies (deployed once)
- Function A: 5 MB code
- Function B: 5 MB code
- Function C: 5 MB code
- Total: 55 MB storage (59% reduction)
- Deploy time: 20 seconds (layer) + 3 seconds × 3 = 29 seconds (36% faster)
```

### 2. Inconsistent Versions

**Problem:** Each function may use different library versions.

**Example:**
```
Function A: requests==2.28.0
Function B: requests==2.30.0  # Different version!
Function C: requests==2.28.0
```

**Issues:**
- Bugs in one version affect some functions
- Difficult to track which functions have which versions
- Security patches require updating each function individually

### 3. Update Complexity

**Problem:** Updating shared code requires redeploying all functions.

**Scenario:**
```
Bug found in shared utility module used by 20 functions.

Without Layers:
1. Update utility code in 20 function directories
2. Run tests for all 20 functions
3. Deploy all 20 functions
4. Verify all 20 functions
Time: 2-3 hours

With Layers:
1. Update utility code in layer
2. Run tests for layer
3. Deploy layer (all functions automatically use new version)
4. Verify representative sample
Time: 20-30 minutes (85% faster)
```

### 4. Deployment Package Size Limits

**Problem:** 250 MB unzipped limit reached faster.

**Example:**
```
Function needs:
- pandas (60 MB)
- numpy (50 MB)
- scipy (70 MB)
- Business logic (10 MB)
Total: 190 MB (76% of limit)

Adding another library fails:
- scikit-learn (80 MB)
Total: 270 MB (exceeds 250 MB limit!) ❌
```

**With Layers:**
```
Layer 1: pandas + numpy (110 MB)
Layer 2: scipy + scikit-learn (150 MB)
Function: Business logic (10 MB)
Total per layer: Within limits ✅
```

---

## CORRECT APPROACH

### 1. Create Lambda Layers for Shared Dependencies

**Layer Structure:**
```
layer/
└── python/          # For Python runtime
    ├── lib/
    │   └── python3.9/
    │       └── site-packages/
    │           ├── requests/
    │           ├── boto3/
    │           └── numpy/
    └── shared_utils/  # Custom shared code
        ├── __init__.py
        ├── api_client.py
        ├── validators.py
        └── formatters.py
```

**Create Layer:**
```bash
# Build layer
mkdir -p layer/python
pip install -r requirements.txt -t layer/python

# Package layer
cd layer
zip -r layer.zip python/

# Deploy layer
aws lambda publish-layer-version \
    --layer-name shared-dependencies \
    --zip-file fileb://layer.zip \
    --compatible-runtimes python3.9
```

**CloudFormation/SAM:**
```yaml
Resources:
  SharedDependenciesLayer:
    Type: AWS::Serverless::LayerVersion
    Properties:
      LayerName: shared-dependencies
      Description: Common dependencies (requests, boto3, numpy)
      ContentUri: ./layer/
      CompatibleRuntimes:
        - python3.9
      RetentionPolicy: Retain  # Keep old versions
  
  MyFunction:
    Type: AWS::Serverless::Function
    Properties:
      Layers:
        - !Ref SharedDependenciesLayer
```

### 2. Organize Layers by Update Frequency

**Strategy:**
```
Layer 1 (Rarely Changes):
- Core libraries (boto3, requests)
- Update: Once per quarter

Layer 2 (Occasionally Changes):
- Domain-specific libraries (pandas, numpy)
- Update: Once per month

Layer 3 (Frequently Changes):
- Custom shared utilities
- Update: Multiple times per week
```

**Benefit:** Functions using Layer 1 unaffected by Layer 3 updates.

### 3. Version Layers Properly

```yaml
# Production uses specific version
MyFunction:
  Properties:
    Layers:
      - !Sub 'arn:aws:lambda:${AWS::Region}:${AWS::AccountId}:layer:shared-dependencies:5'
      # Version 5 pinned (stable)

# Development uses $LATEST
DevFunction:
  Properties:
    Layers:
      - !Sub 'arn:aws:lambda:${AWS::Region}:${AWS::AccountId}:layer:shared-dependencies'
      # No version = $LATEST (auto-update)
```

---

## LAYER BEST PRACTICES

### 1. Layer Size Limits

**Constraints:**
- Max 5 layers per function
- Max 250 MB unzipped total (function + all layers)
- Max 50 MB zipped per layer

**Strategy:**
```
Heavy Library Layer (pandas: 60 MB)
└── One library per layer if large

Light Libraries Layer (requests + boto3: 15 MB)
└── Group small libraries together

Custom Code Layer (5 MB)
└── Shared utilities, separate from dependencies
```

### 2. Layer Naming Convention

```
{organization}-{purpose}-{runtime}-{version}

Examples:
mycompany-common-libs-python39-v1
mycompany-data-processing-python39-v2
mycompany-api-client-python39-v5
```

### 3. Testing Layers

```python
# test_layer.py - Verify layer works

import sys
import os

def test_layer_imports():
    """Test layer dependencies importable"""
    # Should work if layer configured correctly
    import requests
    import boto3
    import numpy as np
    
    # Test versions
    assert requests.__version__ >= "2.28.0"
    assert np.__version__ >= "1.21.0"
    
    print("✅ Layer dependencies imported successfully")

def test_custom_utilities():
    """Test custom shared code"""
    from shared_utils import api_client, validators
    
    # Test utility functions
    assert validators.validate_email("test@example.com")
    
    print("✅ Custom utilities work")

if __name__ == "__main__":
    test_layer_imports()
    test_custom_utilities()
```

---

## DETECTION

### Signs You Need Layers

```
[ ] Same libraries in 5+ function requirements.txt
[ ] Same utility code copied across functions
[ ] Functions approaching 250 MB limit
[ ] Library updates require redeploying many functions
[ ] Inconsistent library versions causing bugs
[ ] Deployment times > 30 seconds per function
```

### Code Smell Example

**Duplicated `requirements.txt`:**
```
# function-a/requirements.txt
requests==2.28.0
boto3==1.26.0
pandas==1.5.0

# function-b/requirements.txt
requests==2.28.0  # Duplicate!
boto3==1.26.0     # Duplicate!
pandas==1.5.0     # Duplicate!

# function-c/requirements.txt
requests==2.28.0  # Duplicate!
boto3==1.26.0     # Duplicate!
pandas==1.5.0     # Duplicate!
```

**Duplicated utility code:**
```
# function-a/utils/api_client.py
def make_request(url, method="GET"):
    # Implementation...

# function-b/utils/api_client.py
def make_request(url, method="GET"):
    # SAME implementation duplicated!

# function-c/utils/api_client.py
def make_request(url, method="GET"):
    # SAME implementation duplicated!
```

---

## REAL-WORLD IMPACT

### Case Study: Microservices Platform

**Before Layers (25 Lambda functions):**
```
Storage:
- 25 functions × 45 MB = 1,125 MB total
- Each with pandas + numpy + requests

Deployment:
- Update pandas: Redeploy 25 functions
- Time: 15 seconds × 25 = 6.25 minutes
- Risk: High (25 separate deployments)

Maintenance:
- Track versions across 25 requirements.txt files
- Inconsistencies led to 3 production bugs/month

Cost:
- Storage: $1.13/month (1,125 MB)
- Deploy time: 6+ minutes per update
```

**After Layers (25 functions + 2 layers):**
```
Storage:
- Layer 1 (pandas + numpy): 110 MB
- Layer 2 (requests + boto3): 15 MB
- 25 functions × 5 MB = 125 MB
- Total: 250 MB (78% reduction!)

Deployment:
- Update pandas: Redeploy Layer 1 only
- Time: 25 seconds (96% faster!)
- Risk: Low (single deployment)

Maintenance:
- Track versions in 2 layer files
- Zero version inconsistencies
- 0 bugs from version mismatches

Cost:
- Storage: $0.25/month (250 MB) (78% savings!)
- Deploy time: < 30 seconds per update
```

**Savings:** 78% storage reduction, 96% faster deployments, zero version bugs

---

## EXCEPTIONS

### When NOT to Use Layers

**1. Single-use libraries:**
```
Only one function needs 'pillow' library
→ Include in function, not layer
```

**2. Rapidly changing dependencies:**
```
Development phase with frequent library updates
→ May be easier to bundle temporarily
→ Extract to layer once stable
```

**3. Function-specific versions:**
```
Function A needs requests==2.28.0
Function B needs requests==2.30.0 (breaking changes)
→ Bundle separately until B updated
```

---

## ALTERNATIVES

### 1. Docker Images (Lambda Container Support)

**Use Case:** Very large dependencies (> 250 MB total)

```dockerfile
FROM public.ecr.aws/lambda/python:3.9

# Copy dependencies
COPY requirements.txt .
RUN pip install -r requirements.txt

# Copy function code
COPY app.py .

CMD ["app.lambda_handler"]
```

**Trade-offs:**
- Size limit: 10 GB (vs 250 MB)
- Cold start: Slower (pulling image)
- Complexity: Docker knowledge required

### 2. Shared EFS Mount

**Use Case:** Extremely large shared files (models, data)

```yaml
FileSystem:
  Type: AWS::EFS::FileSystem

AccessPoint:
  Type: AWS::EFS::AccessPoint
  Properties:
    FileSystemId: !Ref FileSystem

MyFunction:
  Properties:
    FileSystemConfigs:
      - Arn: !GetAtt AccessPoint.Arn
        LocalMountPath: /mnt/efs
```

**Trade-offs:**
- Size: Unlimited
- Cold start: Slower (mounting EFS)
- Cost: EFS storage + throughput costs

---

## RELATED PATTERNS

**AWS Lambda:**
- AWS-Lambda-LESS-01 (Cold start optimization - layers help)
- AWS-Lambda-DEC-02 (Memory constraints - layers reduce size)
- AWS-Lambda-LESS-10 (Performance tuning - smaller packages)

**Architecture:**
- DRY principle (Don't Repeat Yourself)
- Dependency management
- Code reuse patterns

---

## CROSS-REFERENCES

**From This File:**
- Lambda deployment best practices
- Dependency management patterns
- Code organization strategies

**To This File:**
- Shared code patterns
- Deployment optimization
- Version management

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial anti-pattern documentation
- Layer benefits and use cases
- Real-world impact metrics (78% storage reduction)
- Best practices for layer organization
- Testing and deployment strategies
- Exceptions and alternatives

---

**END OF FILE**

**Category:** AWS Lambda Anti-Patterns  
**Priority:** Medium (affects deployment efficiency and maintainability)  
**Impact:** 78% storage reduction, 96% faster deployments, zero version inconsistency bugs
