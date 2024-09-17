# Lesson Plan

## **Day 1: Introduction and Understanding the 12 Factors**

**Morning:**
- **Read:** "The Twelve-Factor App" by Heroku (official website) to get an overview of each of the 12 factors.
  - **Link:** [12factor.net](https://12factor.net/)
- **Watch:** A YouTube video or a quick course on 12-factor apps (e.g., “What are 12-Factor Apps?” by Tech Primers or similar).
- **Activity:** Create a summary document with bullet points explaining each factor in your own words. This will reinforce your understanding.

**Afternoon:**
- **Read:** Articles or blog posts that discuss the practical implementation of 12-factor principles in modern applications. Focus on how companies have applied these principles.
- **Activity:** Identify which of the 12 factors you already use in your current or past projects, and which are new to you.

**Evening:**
- **Reflection:** Write down your thoughts on the importance of each factor, and consider which factors might be the most challenging or impactful in a real-world application.
- **Optional:** Discuss your learnings with a peer or mentor who has experience with 12-factor applications.

## **Day 2: Deep Dive into Application of the Factors**

**Morning:**
- **Deep Dive:** Focus on the first four factors (Codebase, Dependencies, Config, and Backing Services). Understand their significance in software architecture.
  - **Activity:** Look at your past or current projects and consider how they align with these factors.
  - **Hands-On:** If possible, set up a simple project (e.g., a small web service) that adheres to these principles.

**Afternoon:**
- **Deep Dive:** Focus on the next four factors (Build, Release, Run, Processes, Port Binding, and Concurrency). 
  - **Activity:** Research how modern CI/CD pipelines implement these factors.
  - **Hands-On:** If you have access to a CI/CD pipeline or cloud environment, experiment with deploying a small application following these principles.

**Evening:**
- **Reflection:** Document challenges you faced when applying these principles. Consider how you might address these in a professional setting.

## **Day 3: Integration and Real-World Scenarios**

**Morning:**
- **Deep Dive:** Focus on the last four factors (Disposability, Dev/Prod Parity, Logs, and Admin Processes). 
  - **Activity:** Think about how these factors influence scalability, reliability, and maintainability in large-scale applications.
  - **Case Study:** Research case studies or articles that describe how companies like Netflix, Heroku, or others have applied these principles.

**Afternoon:**
- **Practice:** Identify a common application you’ve worked on or a hypothetical one, and outline how you would apply the 12 factors to refactor it.
  - **Activity:** Sketch out a simple architecture diagram or flowchart showing how an application might change when adapting to these principles.

**Evening:**
- **Final Preparation:** Review all your notes, summaries, and reflections. Prepare to discuss specific examples during your interview.
  - **Mock Interview:** If possible, practice discussing the 12 factors with a friend or mentor, focusing on how you would apply them to a real-world application.

By following this plan, you'll be well-prepared to understand and discuss 12-factor applications in your interview.

# **Day 1: Introduction and Understanding the 12 Factors**
The twelve factors are a collection of principles intended to address the most common issues and systemic problems with modern software-as-a-service app development:

https://12factor.net/

## I. Codebase

There is one repo / codebase per app. If multiple apps share the same code, this should be handled by isolating the shared code to libraries. The libraries are then consumed through dependency management.

Each running instance of the app is called a *deploy*.

## II. Explicitly declare and isolate dependencies.

An app should not use system-wide dependencies:

> Libraries installed through a packaging system can be installed system-wide (known as “site packages”) or scoped into the directory containing the app (known as “vendoring” or “bundling”).
>
> **A twelve-factor app never relies on implicit existence of system-wide packages**. It declares all dependencies, completely and exactly, via a *dependency declaration* manifest. Furthermore, it uses a *dependency isolation* tool during execution to ensure that no implicit dependencies “leak in” from the surrounding system. The full and explicit dependency specification is applied uniformly to both production and development.

The app should never need to "shell out" to a system tool for dependency installation.  For example, it should not use `curl` to acquire a dependency. Instead, the tool should be vendored into the app.

## III. Config

An app's *config* is everything that could vary between individual deploys. Consequently, the config should be factored out of the app's code.

The twelve-factor app handles app config through *environment variables* (or *env vars* or *env*). This prevents any app config from being committed to the repo.

Also, it is strongly discouraged to batch config into named groups based on deployments, such as `development`, `prod`, `qa`, `staging`, ... This method does not scale cleanly.

Instead, environment variables should be handled individually, orthogonal to all other env vars.

## IV. Backing Services

Any backing services (datastores, messaging / queueing systems, SMTP servers, etc) should be treated as attached resources. This means that:

* Twelve-factor apps make no distinction between local backing services and third-party backing services. Each is just an attached resource, with its connection defined by an address in the app *config*.

* Resources can be detached and attached from the deploy at will. If a specific resource is misbehaving, it could be detached. A stable backup could then be spun up and attached to support the deploy.

## V. Build, Release, Run

The three stages should be explicitly separated.

* The build stage collects any dependencies, compiles binaries and assetes, and produces an executable bundle called a *build*.

* The release stage combines the build bundle with any configuration to produce a *release* that is ready for immediate execution in the execution environment.

* The run stage launches select processes from the app's release.

Deployment tools should contain release management tools. Each release should have a unique version number. The release ledger is append-only, and a release cannot be mutated once it is created.

## VI. Processes

Twelve-factor apps are stateless and share nothing. The memory space and filesystem can be used as a temporary cache. The twelve-factor app never assumes that anything cached in memory or on disk will be available on a future request or job.

Even if the app is a single process, a restart (from a code change, config change, or relocation of the process to a new execution environment) will wipe out any past state.

For this reason, session state data is a good candidate for a datastore that offers time-expiration, such as Memcached or Redis.

## VII. Port Binding

The twelve-factor app is self-contained and does not rely on being injected into a webserver to create a web-facing service. Instead, the app exports HTTP as a service by binding to a port and listening on that port.

Then during the deployment phase, a routing layer handles routing requests from an external hostname to the web app's service port.

This port-binding approach also allows one twelve-factor app to be the backing service of another twelve-factor app. The URL of the backing-service app will be injected in the config of the consuming app. 

## VIII. Concurrency

In the twelve-factor app, processes are first class citizens. Processes in the twelve-factor app take cues from the unix process model.

Each type of work is assigned a *process type*. For example, HTTP requests may be handled by a web process, and long-running background tasks handled by a worker process.

> The process model truly shines when it comes time to scale out. The share-nothing, horizontally partitionable nature of twelve-factor app processes means that adding more concurrency is a simple and reliable operation. The array of process types and number of processes of each type is known as the *process formation*.

Twelve-factor app processes should rely on the operating system's process manager to manage output streams, respond to crashed processes, and handle user-initiated restarts and shutdowns.

## IV. Disposability

The twelve-factor app's processes can be started or stopped at a moment's notice.

On startup, a process will take no more than a few seconds from the launch command to be able to handle requests.

Processes should be able to handle a `SIGTERM` command from the operating system to gracefully shutdown. In the case of a web app, shutdown will involve the following steps:

* Stop listening on the service port.
* Handle any outstanding requests, which should take no more than a few seconds.
* Exiting.

In the case of a twelve-factor app that uses long polling, the process should just exit. The client will then automatically re-connect to the new instance.

For a worker process, graceful shutdown involves returning a `NACK`. The queueing backend can then add the job back onto the queue.

All jobs should be [reentrant](http://en.wikipedia.org/wiki/Reentrant_%28subroutine%29). This can be achieved  either by using transactions, or alternatively by making operations [idempotent](https://en.wikipedia.org/wiki/Idempotence).

## X. Dev / Prod Parity

Keep the development, staging, and production environments as similar as possible. This involves minimizing the following three types of gaps that typically occur:

* *The time gap*: Historically, a developer may take days, weeks, or even months before their own is deployed to production. Instead, in a twelve-factor app the time between deploys should be measured in hours.

* *The personnel gap*: Historically, a developer would write the code, and a devops engineer would deploy the code. Instead, in a twelve-factor app the developer will handle both responsiblities.

* *The tools gap*: Historically, developers would use more light-weight tech stack like Nginx and SQLite for development, versus Apache and Postgresql for production. Instead, in a twelve-factor app the tools will be as similar as possible in all environments. 

The tools gap can be minimized by using libraries that support multiple adapters, so that the app's code remains the same for integration with the library. Then different adapters can be used in each environment to address differences in tooling.

Still, it is preferred to keep the tools as similar as possible between development and production. Modern provisioning tools like Puppet and Chef, combined with light-weight virtualization tools like Docker and Vagrant, allow for easy creation of VM environments with the correct backing services for development purposes.

## XI. Logs

Logs should be treated as streams of events.

> Logs are the stream of aggregated, time-ordered events collected from the output streams of all running processes and backing services. Logs in their raw form are typically a text format with one event per line (though backtraces from exceptions may span multiple lines). Logs have no fixed beginning or end, but flow continuously as long as the app is operating.
>
> **A twelve-factor app never concerns itself with routing or storage of its output stream.** It should not attempt to write to or manage logfiles. Instead, each running process writes its event stream, unbuffered, to `stdout`. During local development, the developer will view this stream in the foreground of their terminal to observe the app’s behavior.

In development, the developer will likely view the output directly. In production, the execution environment will route the logs to an appropriate backing service. This could include a data warehousing system such as Hadoop or Hive.

## XII. Admin processes

Admin or management tasks should be handled as one-off processes, executed the same as any other process of the twelve-factor app.  Admin code should ship with application code, to avoid synchronization issues.

The same dependency isolation techniques should be used for running management tasks. For example, if the task is a Python script, it should be executed from a virtual environment with a vendored `bin/python`.

Twelve-factor strongly favors languages which provide a REPL shell out of the box for executing one-off scripts.

## Further Reading

[The New Heroku (Part 1 of 4): The Process Model & Procfile](https://blog.heroku.com/the_new_heroku_1_process_model_procfile)

[The New Heroku (Part 2 of 4): Node.js & New HTTP Capabilities](https://blog.heroku.com/the_new_heroku_2_node_js_new_http_routing_capabilities)

[The New Heroku (Part 3 of 4): Visibility & Introspection](https://blog.heroku.com/the_new_heroku_3_visibility_introspection)

[The New Heroku (Part 4 of 4): Erosion-resistance & Explicit Contracts](https://blog.heroku.com/the_new_heroku_4_erosion_resistance_explicit_contracts)

# Video Tutorials

["What is 12-Factor App?" by KodeKloud](https://www.youtube.com/watch?v=1OhmRmMsGdQ)

["Every Developer NEEDS to Know 12-Factor App Principles" by Travis Media](https://www.youtube.com/watch?v=FryJt0Tbt9Q)
