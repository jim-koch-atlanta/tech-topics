# A Modern Container-Based Deployment Model using Kubernetes on Google Cloud Platform (GCP) with Helm Charts

## 1. **Containers and Docker**
- **Containers** are lightweight, portable units of software that package the application code along with its dependencies, libraries, and configuration files.
- **Docker** is a popular platform for creating, deploying, and running containers. The idea is that by packaging everything together, the application will behave the same way regardless of the environment it runs in.
  
  **Key concepts in Docker:**
  - **Docker Image**: A blueprint for a container, which contains the app and all its dependencies. It is a read-only template.
  - **Docker Container**: A running instance of a Docker image. You can have multiple containers running from the same image.

## 2. **Kubernetes (K8s)**
- **Kubernetes** is an open-source orchestration platform for automating the deployment, scaling, and management of containerized applications. A Kubernetes cluster is a group of nodes (machines) running your containers.
  
  Key components of Kubernetes:
  - **Cluster**: A group of machines (nodes) managed by Kubernetes.
  - **Nodes**: The machines (VMs, physical servers, etc.) that run your containers.
  - **Pods**: The smallest deployable units in Kubernetes. A pod can contain one or more containers and is a layer of abstraction that Kubernetes uses to manage containers.
  - **Services**: These define how pods can communicate with each other or expose applications to the outside world (e.g., through a Load Balancer).
  - **Deployments**: These define the desired state of your application, such as the number of instances (pods) running and updating them without downtime.

## 3. **Helm and Helm Charts**
- **Helm** is a package manager for Kubernetes, and a **Helm chart** is a collection of YAML files that describe a set of Kubernetes resources (like deployments, services, config maps, etc.). Helm simplifies the process of managing Kubernetes applications by bundling everything into reusable templates.
  
  When you "deploy with Helm," you’re essentially using a Helm chart to define what your application needs (e.g., number of replicas, service configuration, etc.). The Helm chart helps manage upgrades and rollbacks easily.

  Key components of Helm:
  - **Chart**: A package of pre-configured Kubernetes resources.
  - **Values**: These are the configurations that you provide to the chart, specifying details like environment-specific values (e.g., production vs. staging).
  - **Release**: A specific deployment of a Helm chart into a Kubernetes cluster. Every time you deploy, it creates or updates a release.

## 4. **GCP Kubernetes Cluster (GKE)**
- **Google Kubernetes Engine (GKE)** is Google’s managed Kubernetes service. It runs and manages Kubernetes clusters for you, handling things like node scaling, security patches, and upgrades.
  
  Your containers (built with Docker) are deployed to the Kubernetes cluster on GCP. GKE automatically manages the scaling and availability of your application by distributing the containers across multiple nodes in your cluster.

## 5. **Putting it All Together**
- **Images and Containers**: Developers create Docker images (that package the app and its dependencies) and push them to a container registry (like Google Container Registry or DockerHub).
- **Kubernetes Cluster**: The GKE cluster manages where and how many containers (pods) run, ensuring they are distributed across nodes and scaled as needed.
- **Helm Charts**: Helm charts are used to automate the deployment of these containers, along with Kubernetes resources (like services, volumes, and config maps). You define all the application-specific details in a Helm chart, and it will deploy the necessary pods, services, and other resources.

## 6. **What's the "Banner"?**
It sounds like "deploying to a banner" might be team-specific terminology. In some cases, it refers to a deployment environment (staging, production, etc.), where you deploy to a certain namespace or banner (like a label) to signify which version of the application is currently running in that environment.

### Steps in a Typical Workflow:
1. **Build**: Developers write code, create Docker images, and push these images to a container registry.
2. **Define Deployment**: Use a Helm chart to specify how your application should run in the Kubernetes cluster (e.g., number of replicas, configurations, etc.).
3. **Deploy**: Helm installs or updates the application on the Kubernetes cluster, creating or updating pods, services, and other resources based on the chart.
4. **Run in Kubernetes**: GKE ensures your app runs smoothly, scaling it up or down based on load, managing failures, and keeping it healthy.

# Docker Image ≈ VM Basebox, Docker Container ≈ Provisioned VM

## **Docker Image ≈ VM Basebox**: 
  - Just like a VM basebox is a preconfigured image (with an OS, some tools, etc.) from which virtual machines (VMs) can be provisioned, a Docker image is a static, pre-built snapshot of an application and all of its dependencies. It doesn't change once it's created, and you can use it as the base to run multiple containers.

## **Docker Container ≈ Provisioned VM**: 
  - A Docker container is similar to a running VM provisioned from the basebox. Just as you might start up multiple VMs from the same basebox, you can start multiple containers from the same Docker image. Each container is a separate instance with its own state, processes, and network, but they all share the same underlying Docker image.

However, containers are much lighter and more efficient than VMs because they share the host system’s kernel, whereas VMs run a full guest OS, which makes them more resource-intensive.

Key differences to keep in mind:
- **Resource Overhead**: VMs are heavier since they include their own OS, while containers only include the necessary libraries and share the host OS kernel, making containers much faster to start and lighter in terms of resource consumption.
- **Isolation**: VMs provide full isolation by virtualizing hardware, whereas containers provide process-level isolation but still share the underlying system’s resources.
  
In short: Docker images are like the "blueprint" (like a VM basebox), and Docker containers are the "running instances" (like provisioned VMs), but containers are much more efficient and lightweight.

Exactly! In Kubernetes, you don’t deploy individual containers directly. Instead, you deploy **pods**, which can contain one or more containers. Here's why:

# Why Pods Instead of Direct Containers?

### **Pods as Wrappers**
A **pod** is the smallest deployable unit in Kubernetes, not a container. Even though it often contains just one container, it’s essentially a "wrapper" around one or more containers. The pod adds a layer of abstraction and management to containers, making Kubernetes more flexible.

### **Multiple Containers in a Pod**
While many pods contain a single container (which is the most common case), sometimes multiple tightly-coupled containers are placed together in the same pod. These containers share the same network and storage, allowing them to communicate with each other more easily (for example, when they need to work closely together, like a web server container and a logging sidecar container).

### **Pod Lifecycle and Management**
   - Kubernetes **schedules** pods, not individual containers. It ensures that each pod runs on a node in your cluster, monitors it, and can restart or replace it if necessary.
   - Pods are ephemeral—Kubernetes will create or destroy entire pods based on the desired state of your application (like scaling or rolling updates).
   - All containers within a pod share the same network namespace, meaning they can communicate via `localhost`, which simplifies some networking tasks.

### **Single-container Pods**
In practice, when people talk about "deploying a container," they often mean deploying a **single-container pod**, as this is by far the most common use case. But technically, Kubernetes is still managing pods, even if those pods only contain one container.

### Pod vs Container Deployment in Kubernetes:
- **Containers run inside pods**: Kubernetes always manages pods, so it’s the pod you deploy, not the container directly.
- **Pods provide better abstraction**: Pods give Kubernetes a way to manage a group of containers, even if there's only one container most of the time.
  
In summary, you don't deploy a container directly in Kubernetes; you deploy a **pod**, which then runs the container. A pod is the fundamental unit of deployment, even when it only contains a single container.