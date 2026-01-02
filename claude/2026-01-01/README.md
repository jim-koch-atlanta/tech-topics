## Setting up Claude Code

Instructions are from https://code.claude.com/docs/en/overview.

* I created a Claude.ai account at https://claude.ai/.
* I ran the command `curl -fsSL https://claude.ai/install.cmd -o install.cmd && install.cmd && del install.cmd`.
* I added `C:\Users\jimko\.local\bin\` to my PATH.

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

