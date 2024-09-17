# Use the official Golang image as the base image
FROM golang:1.23.1

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