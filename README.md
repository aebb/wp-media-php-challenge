### PHP Technical Assessment Template

#### Task 1: Build an app or plugin that delivers the desired outcome
We want you to build a PHP app or WordPress plugin that provides the desired outcome.

##### User Story
As an administrator, I want to see how my website web pages are linked together to my home page so that I can manually search for ways to improve my SEO rankings.

##### The What
- Add a back-end admin page (for WP - settings page) where the admin can log in and then manually trigger a crawl and view the results.
- When the admin triggers a crawl:
    - Set a task to run immediately.
    - Then set it to run the crawl every hour ⏰🤖.
- The crawl task:
    - Delete the results from the last crawl (i.e. in temporary storage), if they exist.
    - Delete the sitemap.html file if it exists.
    - Start at the website’s root URL (i.e. home page)
    - Extract all of the internal hyperlinks, i.e. results.
    - Store results temporarily in the database.
    - Display the results on the admin page.
    - Save the homepage content as a static .html file, in the directory of your choice.
    - Create a sitemap.html file that shows the results as a sitemap list structure.
- When the admin requests to view the results, pull the results from storage and display them on the admin page.
- If an error happens, display an error notice to inform of what happened and guide for what to do.
- On the front-end, allow a visitor to view the sitemap.html page.

##### Simplifying the Task:
Let’s keep this simple by:

- Only crawl the home webpage, i.e. instead of recursively crawling through all of the internal hyperlinks.
- Only delete the temporary stored results based on time. Normally, we would also delete them when a change in the content happens. But let’s keep it really simple and only delete based on time.
- For storage, you can use a database (MariaDB or MySQL) or the filesystem.

##### App or Plugin Expectations
- Content on the home page is dynamic.
- It lives in a GitHub repo and retains its history.
- It’s built using our assessment template.
- It’s built with modern OOP with PSR. It uses procedural where it makes sense.
- It’s complete and works.
- It delivers the right expected outcome per what the admin requested (per the user story).
- It does not generate errors, warnings, or notices.
- It runs on the following versions of PHP: 7.0 and up.
- If built with WordPress, it runs on version 5.2 and up.
- It does not create new global variables.
- Use a MariaDB or MySQL database.

##### 🎖Bonus Points
Want extra credit? Cool, here are some bonus opportunities for you:

- Your app or plugin passes PHP Code Sniffer inspection (The PHP CS configuration file is included in this repository).
- Automatically test that your code works as expected by writing unit and integration tests. Need inspiration? Check out our [GitHub](https://github.com/wp-media/wp-rocket/tree/develop/tests).

#### Task 2: Explain It
Add an `Explanation.md` file to your repo that explains:

- The problem to be solved in your own words
- A technical spec of how you will solve it
- The technical decisions you made and why
- How the code itself works and why
- How your solution achieves the admin’s desired outcome per the user story

Think out loud. We want to know:

- How you approach a problem
- How you think about it
- Why you chose this direction
- Why this direction is a better solution

🎉 **Our hope is that you enjoy this technical assessment. *Have fun with it!*** 🎉 

#### FAQ

##### Can I use third-party libraries?
Yes. You are free to use to choose any third-party library. Just make sure to explain why you choose each and how you are using them.

##### Do my git commit history matter?
Yes it does. Why? It shows us how you work.

##### Where do I ask questions?
Create an issue in your assessment repo. Let's discuss things there. Ping us. We are happy to help and collaborate.
