# Coursera: AWS Cloud Technical Essential: Storage on AWS (cont.)

See https://www.coursera.org/learn/aws-cloud-technical-essentials/lecture/BK8vM/choose-the-right-storage-service

## Recap of Amazon Storage Options

Here are the different Amazon storage options we've discussed so far.

### Amazon EC2 Instance Store

Instance store is an ephemeral block storage. This is preconfigured storage that exists on the same physical server that hosts the EC2 instance and cannot be detached from Amazon EC2. You can think of it as a built-in drive for your EC2 instance. Instance store is generally well-suited for temporary storage of information that is constantly changing, such as buffers, caches, and scratch data. It is not meant for data that is persistent or long-lasting. If you need persistent long-term block storage that can be detached from Amazon EC2 and provide you more management flexibility, such as increasing volume size or creating snapshots, then you should use Amazon EBS.

### Amazon EBS

Amazon EBS is meant for data that changes frequently and needs to persist through instance stops, terminations, or hardware failures. Amazon EBS has two different types of volumes: SSD-backed volumes and HDD-backed volumes. SSD-backed volumes have the following characteristics:

* Performance depends on IOPS
* Ideal for transactional workloads such as databases and boot volumes

HDD-backed volumes have the following characteristics:

* Performance depends on MB/s
* Ideal for throughput-intensive workloads, such as big data, data warehouses, log processing, and sequential data I/O

Here are a few important features of Amazon EBS that you need to know when comparing it to other services:

* It is block storage.
* You pay for what you **provision**.
* EBS volumes are replicated across multiple servers in a single Availability Zone.
* Most EBS volumes can only be attached to a single EC2 instance at a time.

### Amazon Simple Storage Solution (S3)

If your data doesn't change that often, Amazon S3 might be a more cost-effective and scalable storage solution. S3 is ideal for storing static web content and media, backups and archiving, data for analytics, and can even be used to host entire static websites with customer domain names. Here are a few important features of Amazon S3 to know about when comparing it to other services.

* It is object storage.
* You pay for what you **use**.
* Amazon S3 replicates your obbjects across multiple Availability Zones in a Region.
* Amazon S3 is not storage attached to compute.

### Amazon Elastic File System (EFS) and Amazon FSx

For file storage that can mount onto multiple EC2 instances, you can use Amazon Elastic File System (EFS) and Amazon FSx:

* **Amazon EFS**: Fully manage NFS file system.
* **Amazon FSx for Windows File Server**: Full managed file server built on Windows Server that supports the SMB protocol.
* **Amazon FSx for Lustre**: Fully managed Lustre file system that integrates with S3.

Here are some important features of Amazon EFS and FSx:

* It is file storage.
* You pay for what you **use**.
* Amazon EFS and FSx can be mounted onto multiple EC2 instances.

## Next

https://www.coursera.org/learn/aws-cloud-technical-essentials/lecture/3c2r5/explore-databases-on-aws