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

# Pulling
## Prerequisites
1. Download Docker
2. Download DDEV
3. Download Git

## Startup
1. **Clone the project**: `git clone https://github.com/edenlim/BoNotes.git`
2. **Change directory to BoNotes**: `cd BoNotes`
3. **Start up docker environment**: **ALWAYS** run `ddev start`, to start the docker environment.
    - When working on BoNotes, your **MUST** run `ddev start` to run the project. **ALWAYS**
4. **Install Dependencies**: `ddev composer install`
5. **Check what websites are available**: `ddev describe`
    - **Frontend**: `http://bonotes.ddev.site`
    - **Backend Database**: `http://bonotes.ddev.site:8036`

---

I am unsure about these two:
6. **Setup Environment File**: 
   - `cp .env.example .env`
   - `ddev artisan key:generate`
7. **Migrate Database**: `ddev artisan migrate`