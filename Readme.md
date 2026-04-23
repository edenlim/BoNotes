# Initalisation step

1. Congifure laravel project with ddev
    - `ddev config --project-type=laravel --docroot=public --create-docroot`
        - `ddev config`: Sets up a ddev configuration file
        - `--project-type=laravel`: Configuration set to laravel framework
        - `--docroot=public`: Sets the web root to Laravel’s required `public/` directory, ensuring all requests go through `index.php` while keeping sensitive files (e.g. `.env`, `vendor/`) inaccessible.
        - `--create-docroot`: Create the docroot `public` if it does not exists yet
    
2. `ddev start`

3. `ddev composer create laravel/laravel`
    - **Composer**: The tool used to handle PHP related libraries, similar to `npm` for Node.js
    - `ddev composer`: Run Composer within the ddev environment
    - `create laravel/laravel`: Composer command:
        - Downloads the official Laravel starter project
        - Installs all dependencies in `vendor/`
        - Sets up the default folder structure


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