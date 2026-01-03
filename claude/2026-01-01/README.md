## Setting up Claude

Instructions are from https://code.claude.com/docs/en/overview.

* I created a Claude.ai account at https://claude.ai/.
* I ran the command `curl -fsSL https://claude.ai/install.cmd -o install.cmd && install.cmd && del install.cmd`.
* I added `C:\Users\jimko\.local\bin\` to my PATH.

I then ran `claude` from the command line. It brought up a chat at `https://claude.ai`, which was not really what I wanted.

## Setting up Claude Code

I then installed the Claude Code for VS Code extension. This appears to give me the sort of experience that I expected.

I'll likely try the Claude CLI again in the future, but I'm going to stick with Claude Code for VS Code for now.

## Setting up my project

I prompted:

> Hi Claude! Could you design a software architecture? If you need more information from me, ask me 1-2 key questions right away. If you think I should upload any documents that would help you do a better job, let me know. You can use the tools you have access to — like Google Drive, web search, etc. — if they’ll help you better accomplish this task. Do not use analysis tool. Please keep your responses friendly, brief and conversational.

> Please execute the task as soon as you can - an artifact would be great if it makes sense. If using an artifact, consider what kind of artifact (interactive, visual, checklist, etc.) might be most helpful for this specific task. Thanks for your help!

Claude asked for additional details. I responded:

> I would like to build a web app written in PHP. It needs to use Apache for the web server. It needs to run within Kubernetes pods.

> * It needs to allow the user to upload a GIF file.
> * Uploaded GIFs can be transformed using ImageMagick. The supported transformations are:
>   * Resize
>   * Crop
>   * Rotate
>   * Flip
> * Once the user has finished transforming the GIF, they can choose to save it.

From this, Claude produced [architecture.md](./architecture.md).

## Setting up the Dockerfile

I prompted:

> Create the Dockerfile.

Claude produced [Dockerfile](./Dockerfile).

## Testing the Dockerfile

`docker-compose up`

This worked successfully. However, uploads failed. Claude identified the issue as a missing upload directory. It explicitly added `/tmp/php_sessions` to the Dockerfile and rebuilt with `docker-compose up -d --build`. After this uploads succeeded.

From there, transforms were failing with the error:

> Network error: Failed to execute 'json' on 'Response': Unexpected end of JSON input

Claude identified several issues:
* Missing `session_start()` calls, to make the session values available.
* An incorrect rewrite rule in `apache-vhost.conf`.
* Missing Apache directory permissions, which led to changing where GIF files are stored.
* The function `build_imagemagick_command()` being defined **after** its use in the `transform.php` file.

## Cleaning up the Dockerfile

After that, I asked Claude to clean up the Dockerfile. This succeeded. The Dockerfile still seems overly complex. However, the project works.

## Next Steps

1. Switch from ImageMagick command-line calls to the ImageMagick PHP extension
2. Switch from storing GIFs in a volume mount to a LocalStack S3 bucket.
3. Add an interface around ImageMagick.
4. JimageMagick.