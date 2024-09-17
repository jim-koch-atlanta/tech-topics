# Hello World with Containers

Let’s walk through the process of creating a basic "Hello World" app in Go, containerizing it using Docker, and running it locally on your Windows 11 machine.

### Step 1: Install Required Tools

Before starting, you’ll need to have the following installed on your laptop:

1. **Go Programming Language**:
   - [Go Installation Guide](https://go.dev/doc/install)
   
2. **Docker Desktop** (for containerization and running containers locally):
   - [Docker Desktop for Windows](https://www.docker.com/products/docker-desktop/)

Make sure both are installed and accessible via your command line/PowerShell.

### Step 2: Create a Basic Hello World App in Go

1. Open a terminal (PowerShell or Command Prompt) and navigate to the folder where you want to create the Go project.

2. Create a directory for your project:
   ```bash
   mkdir hello-go
   cd hello-go
   ```

3. Inside the `hello-go` directory, create a file called `main.go`:
   ```bash
   notepad main.go
   ```

4. Add the following Go code to `main.go`:

   ```go
   package main

   import "fmt"
   import "net/http"

   func handler(w http.ResponseWriter, r *http.Request) {
       fmt.Fprintln(w, "Hello, World!")
   }

   func main() {
       http.HandleFunc("/", handler)
       fmt.Println("Server starting on port 8080...")
       http.ListenAndServe(":8080", nil)
   }
   ```

This Go code creates a simple web server that listens on port 8080 and returns “Hello, World!” when accessed.

### Step 3: Create a Dockerfile

Now, to containerize this app, you need to create a `Dockerfile`, which defines how your application should be built inside a container.

1. In the same `hello-go` directory, create a file called `Dockerfile`:
   ```bash
   notepad Dockerfile
   ```

2. Add the following content to the `Dockerfile`:

   ```Dockerfile
   # Use the official Golang image as the base image
   FROM golang:1.19-alpine

   # Set the working directory inside the container
   WORKDIR /app

   # Copy the Go module files and download the necessary dependencies
   COPY go.mod ./
   COPY go.sum ./
   RUN go mod download

   # Copy the rest of the app source code into the working directory
   COPY . .

   # Build the Go app inside the container
   RUN go build -o hello-go

   # Expose port 8080 to access the app
   EXPOSE 8080

   # Command to run the app
   CMD ["./hello-go"]
   ```

This `Dockerfile`:
- Uses a lightweight **Golang Alpine** base image.
- Sets up the working directory and installs dependencies.
- Copies the Go source files into the container and compiles the app.
- Exposes port 8080 (the port the Go app listens on).
- Specifies the command to run the compiled Go app.

### Step 4: Initialize Go Modules (Optional)

To set up Go modules for dependency management, run the following command inside the `hello-go` directory:

```bash
go mod init hello-go
```

Then, generate a `go.sum` file by running:

```bash
go mod tidy
```

### Step 5: Build the Docker Image

Now it’s time to build your Docker image. Run the following command in the terminal:

```bash
docker build -t hello-go .
```

- This will create a Docker image named `hello-go`.
- Docker will go through the instructions in the `Dockerfile` and package your app into the image.

### Step 6: Run the Docker Container Locally

Once the Docker image is built, you can run it locally using Docker:

```bash
docker run -p 8080:8080 hello-go
```

- The `-p 8080:8080` flag maps port 8080 on your local machine to port 8080 in the container. This allows you to access the Go app from your browser.

### Step 7: Test the App

1. Open your browser and go to `http://localhost:8080`.
2. You should see the message **"Hello, World!"** displayed in the browser.

Congratulations! You’ve successfully created a Go app, containerized it using Docker, and run it locally on your Windows 11 machine.

### Bonus: Cleanup

To stop the container, press `Ctrl+C` in the terminal where the container is running.

You can also stop it using Docker commands:
```bash
docker ps  # Find the container ID
docker stop <container_id>
```

To remove the container:
```bash
docker rm <container_id>
```

And to remove the image if you no longer need it:
```bash
docker rmi hello-go
```

---

That’s the complete workflow for building and running a simple containerized Go application! Let me know if you need further clarification or run into any issues.