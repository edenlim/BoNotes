# Table of Content
1. [Initialisation Step](#initalisation-step)
2. [Pulling and Execution](#pulling-and-execution)
3. [Libraries and Framework](#libraries-and-framework)
4. [Programming Conventions](#programming-conventions)
5. [List of Commands](#list-of-commands)
6. [Good Read](#good-read)

# Initalisation step
1. Congifure laravel project with ddev
    - `ddev config --project-type=laravel --docroot=public --create-docroot`
        - `ddev config`: Sets up a ddev configuration file
        - `--project-type=laravel`: Configuration set to laravel framework
        - `--docroot=public`: Sets the web root to Laravel’s required `public/` directory, ensuring all requests go through `index.php` while keeping sensitive files (e.g. `.env`, `vendor/`) inaccessible.
        - `--create-docroot`: Create the docroot `public` if it does not exists yet
    
2. `ddev start`

3. `ddev composer create-project laravel/laravel .`
    - **Composer**: The tool used to handle PHP related libraries, similar to `npm` for Node.js
    - `ddev composer`: Run Composer within the ddev environment
    - `create-project laravel/laravel`: Composer command:
        - Downloads the official Laravel starter project
        - Installs all dependencies in `vendor/`
        - Sets up the default folder structure
    - `.`: Creates at the current level

4. `ddev add-on get ddev/ddev-phpmyadmin`
    - Downloads the phpmyadmin addon to have a frontend GUI for the backend table
    - Run `ddev restart` to reflect changes

# Pulling and Execution
## Prerequisites
1. Download Docker
2. Download DDEV
3. Download Git

## Startup
1. **Clone the project**: `git clone https://github.com/edenlim/BoNotes.git`
2. **Change directory to BoNotes**: `cd BoNotes`
3. **Start up docker environment**: 
   - Make sure your docker app is **OPENED** 
   - **ALWAYS** run `ddev start`, to start the docker environment.
   - When working on BoNotes, your **MUST** always use the command `ddev start` to run the project. **ALWAYS**
   - All console commands need to be run in docker container by appending `ddev` to the front
4. **Install Dependencies**: `ddev composer install`
5. Also run `ddev npm install`
6. You need to update the `.env` file. The configuration is found in discord resource channel. **Copy and Paste** that into your .env file
7. You also need to import database dump file. The folder is also in the discord resource channel. 
   - Download the folder and move the whole folder into the root folder of the project
   - Run the code `ddev import-db --file=./dump_07_05_2026.sql.gz`
8. Build the tailwind cache by running `ddev npm run rebuild`. **Check what websites are available**: `ddev describe`
    - **Frontend**: `http://bonotes.ddev.site`
    - **Backend Database**: `http://bonotes.ddev.site:8036`


---

I am unsure about these two for now due to backend:
6. **Setup Environment File**: 
   - `cp .env.example .env`
   - `ddev artisan key:generate`
7. **Migrate Database**: `ddev artisan migrate`

# Libraries and Framework
## Laravel
### Blade
Blade is the templating engine of laravel- it replaces our traditional HTML file
- Blade are stored in `./resources/views`.
  - We store non-pages under `./resources/views/components`
  - We render the components we want using `<x-(path and component file name)>`
- Provide conditional rendering
  - `@if`, `@foreach`
- Access data using `{{$variable_name}}` which is protected from XSS
- Pass and Inherits parameters, including setting default value using `:var="something"` and `@props(['name'=> "Default value"])`
- Flexible layouting, including injecting components using `{{ $slot }}`
- `yield` `extends` `section`: 
  - `yield`: Is used in layouts. This leaves a blank promise in `File A` that another file will populate the missing data section `S`
  - `extends`: `File B` extends `File A`, saying it will provide `File A` the missing section `S`
  - `section`: The element that will be injected, called `S`
## Tailwind
Tailwind allows you to inline style elements using classes.

Tailwind base unit is 0.25rem == 4px.

When running, tailwind compiles and caches only what classes it needs to use, to not bloat the project. 
- As such, changes might not appear on refresh. 
- When adding a new tailwind class, use `ddev npm run rebuild` to flush the cache.

## AlpineJS
AlpineJS allows you to add small interactive logic while staying in HTML file (.blade.php files)  

- `x-data`: Declares variables, that HTML elements can access
- `x-on` or `@`: Event listeners
- `x-show` or `if`: conditional rendering
- `x-teleport`: Allows you to write a code in a file, but have it removed and appended at the end of another, specified element

# Programming Conventions:
1. Don't hardcode
2. Don't Repeat Yourself
3. Don't use `px`
   - Use `rem` (Relative EM size). This is because every device has different pixel density, so if you use px, it may look kaputt in some devices

# List of Commands:
1. `ddev npm run rebuild`: Custom Creation in package.json: 
    - Delete static precompiled builds
    - Runs vite build

# Good Read
- [Conventional Commit](https://www.conventionalcommits.org/en/v1.0.0/): How to properly leave a commit message
- [Sementic Versioning](https://semver.org/): How to properly version the project
