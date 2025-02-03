# Coursera: AWS Cloud Technical Essentials: AWS Networking (cont.)

See https://www.coursera.org/learn/aws-cloud-technical-essentials/assignment-submission/rbU7N/ready-for-the-lab

## Week 2 Exercise & Assessment

### Lab 2: Creating a VPC and Launching a Web Application in an Amazon EC2 Instance

Goals of this lab:

* Create a new Amazon VPC with two public subnets
* Create an Internet Gateway
* Create a Route Table with a Public Route to the Internet
* Create a Security Group
* Launch an Amazon Elastic Compute Cloud (Amazon EC2) instance
* Configure an EC2 instance to host a web application using a user data script

Once again, we used this script for the EC2 instance's User Data:
```
#!/bin/bash -ex

# Update yum
yum -y update

# installs nvm (Node Version Manager)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.0/install.sh | bash

export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion

# download and install Node.js (you may need to restart the terminal)
nvm install 20

# Create a dedicated directory for the application
mkdir -p /var/app

# Get the app from S3
wget https://aws-tc-largeobjects.s3-us-west-2.amazonaws.com/ILT-TF-100-TECESS-5/app/app.zip

# Extract it to the desired folder
unzip app.zip -d /var/app/
cd /var/app/

# Install dependencies
npm install

# Start the app
npm start
```

## Reminder

* By default, network ACLs allows all traffic in and out of the subnet. Network ACLs are considered **stateless**, which is why we need to include both the inbound and outbound ports used for the protocol.

* By default, security groups block all inbound traffic and allows all outbound traffic. Security groups are considered **stateful**, meaning they will remember if a connection is originally initiated by the EC2 instance or from the outside and temporarily allow traffic to respond without having to modify the inbound rules.

## Next

https://www.coursera.org/learn/aws-cloud-technical-essentials/lecture/inyuH/introduction-to-week-3