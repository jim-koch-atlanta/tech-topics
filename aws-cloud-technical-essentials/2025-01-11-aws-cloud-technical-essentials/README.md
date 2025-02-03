# Coursera: AWS Cloud Technical Essentials: Monitoring on AWS

See https://www.coursera.org/learn/aws-cloud-technical-essentials/lecture/26rKT/introduction-to-week-4.

## Monitoring on AWS

When you have a solution comprised of many different components, it's important to be able to see how different services operate over time. We want the ability to respond to issues before they are reported by the end user.

**Metrics** = Data points generated from services.
**Statistics** = Metrics / Time.

In the case of an EC2 instance, there are many different metrics that can be collected and analyzed:

* CPU utilization
* Network utilization
* Disk performance
* Memory utilization
* Logs created by the applications

AWS already has a service for monitoring called Amazon CloudWatch. Amazon CloudWatch allows you to:

* Detect anomalous behavior in your environments.
* Set alarms to alert you when something’s not right.
* Visualize logs and metrics with the AWS Management Console.
* Take automated actions like scaling.
* Troubleshoot issues.
* Discover insights to keep your applications healthy.


### Introduction to CloudWatch

Many AWS services send data to CloudWatch at a rate of one data point per metric per 5-minute interval. This is known as **basic monitoring**, and it is available for free.

You can get granularity of a data point each minute with **detailed monitoring**. Pricing depends on the service being monitored.

### Break Down Metrics

Metrics are organized into containers called **namespaces**. Metrics in different namespaces are isolated from each other. It's like having different categories of metrics.

The metrics will have **dimensions** attached to them. A dimension is a name/value pair that is part of the metric’s identity, and it can be used for filtering results.

You can also setup **custom metrics** using the `PutMetricData` API. You can even generate and report **high-resolution custom metrics** that report up to once a second.

### Dashboards

The CloudWatch Console allows you to build dashboards to visualize your application's metrics. These dashboards can contain metrics from across regions in order to have a global view of your application state.

CloudWatch aggregates data according to its timestamp. You can generate dashboards based on historical data, or your metric widgets can display live data.  Live data is data from the last minute that has not been fully aggregated.

### CloudWatch Logs

CloudWatch also has a centralized place for reviewing an application's log data called CloudWatch Logs. CloudWatch Logs can monitor, store, and access log files from Amazon EC2 instances, AWS Lambda functions, and other sources.

You can setup **metric filters** on logs, which turn log data into numerical CloudWatch metrics that can be graphed and used on dashboards.

Setting up CloudWatch Logs on AWS Lambda takes minimal effort. You only need to give the Lambda function the correct IAM permissions to post logs.

For EC2 instances, you need to install and configure the CloudWatch Logs agent onto the EC2 instance. The agent includes the following components

* A plug-in to the AWS CLI that pushes log data to CloudWatch Logs.
* A script that initiates the process to push data to CloudWatch Logs.
* A cron job that ensures the daemon is always running.

### CloudWatch Logs Terminology

**Log event**: A log event is a record of activity recorded by the application or resource being monitored, and it has a timestamp and an event message.  

**Log stream**: A log stream is a sequence of log events that all belong to the same resource being monitored.

**Log groups**: A log group is composed of log streams that all share the same retention and permissions settings. For example, if you have multiple EC2 instances hosting your application and you are sending application log data to CloudWatch Logs, you can group the log streams from each instance into one log group.

### CloudWatch Alarms

You can create CloudWatch alarms to automatically initiate actions based on a sustained state change in metrics. to setup an alarm, you need:

* The metric you want to setup an alarm for
* The threshold value at which the alarm is triggered
* The specified time period that the threshold needs to be crossed to trigger the alarm.

For example, you could say that you want to trigger an alarm if an EC2 instance's CPU utilization goes over a threshold of 80% for 5 minutes or longer.

An alarm has three possible states:

* **OK**: The metrics is within the defined threshold.
* **ALARM**: The metric is outside of the defined threshold.
* **INSUFFICIENT_DATA**: The alarm has just started, the metric is not available, or not enough data is available for the metric.

## Optimizing Solutions on AWS

The availability of a system is typically defined as a percentage of uptime in a given year, and it is often defined in terms of the "number of nines":

* **90% ("one nine")**: 36.53 days of downtime
* **99% ("two nines")**: 3.65 days of downtime
* **99.9% ("three nines")**: 8.77 hours of downtime
...

To increase availability, you need redundancy. Increased redundancy and infrastructure means higher costs, so you need to determine where adding redundancy is no longer viable in terms of revenue.

### Improve Application Availability

In our sample Employee Web App, we used one EC2 instance to host the application, one S3 instance to serve the photos, and one DynamicDB instance to store the structured data.

The single EC2 instance is a single point of failure for the application. One wayt to solve this is by adding more servers.

In addition to possible software issues at the application or operating system level, we need to be mindful of hardware issues. To address this, we would want to deploy a second EC2 instance in a different Availability Zone. That would solve issues with the application or operating system, in addition to addressing hardware issues.

### Manage Replication, Redirection, and High Availability

You need to **create a process for replication** of configuration files, software patches, and the application across instances. This should be automated.

You also need to **address customer redirection**. This could be taken care of by updating the DNS entries. Another option is to use a load balancer that takes care of health checks and distributes load across each server.

Lastly, you need to desgin when having more than one server is the type of availability you need. Should it be an **active-passive** system, or an **active-active** system?

* *Active-Passive*: Only one of the two instances is available at a time. This is beneficial with a stateful application, where data about the client's session is stored on the server.

* *Active-Active*: This is more beneficial in terms of scalability. By having both servers available, the second server can take some of the application load. Stateless applications work better for active-active systems.

## Next

See https://www.coursera.org/learn/aws-cloud-technical-essentials/lecture/fjBGf/amazon-ec2-auto-scaling.