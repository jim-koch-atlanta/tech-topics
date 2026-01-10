## Vibe Coding with Claude

See https://www.coursera.org/learn/introduction-to-claude-code/home/module/1.

## Claude Rules and Plan Mode

It is possible to give Claude some "ground rules" by creating a claude.md file. For example, see [CLAUDE.md](../claude_code_demo/CLAUDE.md).

This ensures Claude thinks in tasks instead of dumping huge chunks of code. The work will be completed in small, trackable steps.

When you're in Claude Code, press Shift+Tab+Tab to enable **Plan Mode**.

Now I type:
```
❯ Create a simple AI educational website in HTML. Use placeholder images, keep the design minimal, but make it look like something from a modern design agency.
```

In my case, it did not automatically pick up the CLAUDE.md file. In plan mode, I had to explicitly tell it to use the file.

## Checkpoints and Security Points

Checkpoints allow you to rollback if something goes wrong. You can either explicitly tell Claude to make a new Git commit when it has complete a change, or you can add it as part of the workflow.

You can also specify for Claude to perform a security review of the code:

> Go through the code you just wrote and confirm it follows security best practices. Check that no sensitive data is exposed or left in the front-end, and that there are no vulnerabilities that an attacker could exploit.

Claude will then review the code and provide feedback.

## Next

https://www.coursera.org/learn/introduction-to-claude-code/lecture/RY9LZ/adding-images