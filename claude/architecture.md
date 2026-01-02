# GIF Transformation Web App - Software Architecture

## System Overview
A containerized PHP web application that enables users to upload, transform, and save GIF files using ImageMagick, running on Kubernetes with Apache.

---

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                      User Browser                            │
│                   (Web UI - JavaScript)                      │
└────────────────────────┬────────────────────────────────────┘
                         │ HTTP/AJAX
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                  Kubernetes Ingress                          │
│              (Routes external traffic)                       │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              Kubernetes Service (LoadBalancer)               │
│         (Distributes traffic across pods)                    │
└────────────────────────┬────────────────────────────────────┘
                         │
        ┌────────────────┼────────────────┐
        ▼                ▼                ▼
   ┌─────────┐      ┌─────────┐      ┌─────────┐
   │  Pod 1  │      │  Pod 2  │      │  Pod N  │
   └────┬────┘      └────┬────┘      └────┬────┘
        │                │                │
        └────────┬───────┴───────┬────────┘
                 │               │
                 ▼               ▼
         ┌──────────────────┬──────────────────┐
         │   Apache 2.4     │  PHP-FPM / CLI   │
         ├──────────────────┼──────────────────┤
         │ - Static assets  │ - GIF processing │
         │ - Routing        │ - ImageMagick    │
         │ - Compression    │ - Sessions       │
         └──────────────────┴──────────────────┘
                 │
                 ▼
         ┌──────────────────┐
         │  PersistentVolume │
         │   (Shared Storage)|
         │                  │
         │ - Uploads/       │
         │ - Transformed    │
         │ - Sessions       │
         └──────────────────┘
```

---

## Component Details

### 1. **Frontend (Web UI)**
- **Technology**: HTML5, CSS3, JavaScript (vanilla or lightweight framework)
- **Features**:
  - File upload input (GIF only)
  - Real-time preview of GIF
  - Transformation controls (buttons/sliders):
    - Resize (width/height inputs)
    - Crop (bounding box tool)
    - Rotate (angle input: -360 to 360°)
    - Flip (horizontal/vertical buttons)
  - Preview of transformations (canvas-based or server-side preview)
  - Save button to download or store result
- **Communication**: AJAX requests to backend API endpoints

### 2. **Backend (PHP Application)**
- **Framework**: Plain PHP or lightweight framework (Laravel, Slim, etc.)
- **Key Endpoints**:
  - `POST /upload` - Receive and store uploaded GIF
  - `POST /transform` - Apply transformations (resize, crop, rotate, flip)
  - `GET /preview` - Return current transformed GIF
  - `POST /save` - Save final GIF and return download link
  - `POST /cancel` - Clear session data
- **Responsibilities**:
  - Session management (track transformation state)
  - Input validation (file type, size limits)
  - ImageMagick command orchestration
  - Temporary file handling
  - Error handling & logging

### 3. **Web Server (Apache 2.4)**
- **Configuration**:
  - Serve static assets (CSS, JS, images)
  - Proxy PHP requests appropriately
  - Enable gzip compression
  - Set security headers
  - CORS if needed
- **Modules**: mod_proxy, mod_rewrite, mod_expires

### 4. **Image Processing**
- **Tool**: ImageMagick (command-line via `exec()` or `shell_exec()`)
- **Operations**:
  - **Resize**: `convert input.gif -resize WIDTHxHEIGHT output.gif`
  - **Crop**: `convert input.gif -crop WIDTHxHEIGHT+X+Y output.gif`
  - **Rotate**: `convert input.gif -rotate ANGLE output.gif`
  - **Flip**: `convert input.gif -flip output.gif` (vertical) or `-flop` (horizontal)
- **Considerations**:
  - Preserve animation frames when transforming
  - Handle large files efficiently
  - Resource limits (CPU, memory, timeout)

### 5. **Storage (PersistentVolume)**
- **Type**: ReadWriteMany (shared across pods)
- **Mount Path**: `/var/www/html/uploads` (or similar)
- **Subdirectories**:
  - `/uploads/incoming/` - User uploads
  - `/uploads/transformed/` - Processed GIFs
  - `/sessions/` - Session state
- **Cleanup Strategy**: Cron job or lifecycle hook to delete old files (>24hrs)

### 6. **Container (Docker)**
- **Base Image**: `php:8.x-apache`
- **Layers**:
  - PHP with GD and ImageMagick extensions
  - Apache modules (mod_rewrite, mod_proxy)
  - Required system libraries
- **Healthcheck**: Simple HTTP endpoint (`/health`)
- **Resource Requests/Limits**:
  - CPU: 100m request, 500m limit
  - Memory: 256Mi request, 512Mi limit

### 7. **Kubernetes Deployment**
- **Deployment Manifest**:
  - Replicas: 3-5 pods
  - Rolling update strategy
  - Resource requests/limits
  - Liveness & readiness probes
- **Service**: ClusterIP or LoadBalancer
- **Ingress**: Route external traffic
- **PersistentVolumeClaim**: Mount storage to pods
- **ConfigMap**: Apache configs, PHP settings
- **Secret**: API keys, credentials (if needed)

---

## Data Flow

```
1. User uploads GIF
   ↓
2. PHP validates & stores in PersistentVolume
   ↓
3. User applies transformations (one or multiple)
   ↓
4. Each transformation:
   - PHP receives request
   - Reads current GIF from storage
   - Calls ImageMagick via exec()
   - Saves result back to storage
   - Returns preview to frontend
   ↓
5. User clicks Save
   ↓
6. PHP finalizes file (rename/move)
   ↓
7. User downloads or accesses saved GIF
```

---

## Key Considerations

### Performance
- Resize large GIFs before storage (set max dimensions)
- Cache previews in session/memory if possible
- Use ImageMagick's optimization flags
- Limit simultaneous transformations per pod

### Scalability
- Kubernetes auto-scaling (HPA) based on CPU/memory
- Stateless pods (session data in PersistentVolume)
- Load balancing across replicas

### Security
- Validate file type (magic bytes, not just extension)
- Sanitize file names
- Set file permissions on PersistentVolume
- Rate limit uploads/transformations
- Scan for malicious content (optional)

### Reliability
- Liveness probes detect crashed pods
- Readiness probes ensure pod is ready for traffic
- PersistentVolume backup strategy
- Error logging and monitoring

### Development/Testing
- Local Docker Compose for development
- Minikube for local Kubernetes testing
- Unit tests for transformation logic
- Integration tests for API endpoints

---

## Technology Stack Summary

| Component | Technology |
|-----------|-----------|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP 8.x |
| Web Server | Apache 2.4 |
| Image Processing | ImageMagick |
| Container | Docker |
| Orchestration | Kubernetes |
| Storage | PersistentVolume (NFS/EBS/etc.) |

---

## Deployment Checklist

- [ ] Dockerfile created & tested locally
- [ ] Apache virtual host configured
- [ ] PHP error handling & logging
- [ ] ImageMagick integration verified
- [ ] Kubernetes manifests (Deployment, Service, Ingress)
- [ ] PersistentVolume & PersistentVolumeClaim set up
- [ ] Resource requests/limits defined
- [ ] Health check endpoints implemented
- [ ] Security headers configured
- [ ] Monitoring & alerting (optional but recommended)
- [ ] Tested with multiple pods (scaling)
