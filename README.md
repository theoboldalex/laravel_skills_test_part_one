# Pegasus Laravel Basics Skills Test

Your task is to implement a backend API (no front-end is required).

These are the requirements for the system:

- User Signup Endpoint
    - A `POST` endpoint, that accepts JSON, containing the following;
        1. user full name,
        2. password,
        3. email address,
        4. created date,
        5. the user's role (acceptable role options are `admin`, `user`).
    - Validation. 
        - The app should check that the fields submitted are not empty. 
        - The app should also check that the password matches the following rules:
            - Between 8 and 64 characters
            - Must contain at least one digit (0-9)
            - Must contain at least one lowercase letter (a-z)
            - Must contain at least one uppercase letter (A-Z)
    - When validation fails the app should return an appropriate status code with error/s that can be used by the client
- Save the signup information to a data store. I recommend something lightweight like SQLite (I have created a database file for you in the repo should you choose to use it).
- User Signup Details
    - A `GET` endpoint that takes a user ID and returns the user details as JSON.
- Create whatever level of testing and documentation you consider appropriate

## What I am looking for

* Submit something that I can run locally
* Commiting changes with good messages as you go is very helpful
* You can update the README or add a NOTES.md detailing any decisions/tradeoffs you made, or changes you would make with more time
* Clean, secure, modular code written to your own standards of what good looks like. Add concise comments in the code if you want to explain a decision. 
* Pragmatism. I am not looking for complex solutions.
* Feel free to install and use additional packages
* NO AI TOOLS TO BE USED