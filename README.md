
# Tavern&Tomes

A Dungeons & Dragons 5e campaign and character management application.

Tavern&Tomes aims to provide players and Dungeon Masters with a centralized platform for managing campaigns, characters, inventories, spells, quests, NPCs, and campaign lore.

## Current Status

🚧 Under active development.

## Technology Stack

- Laravel
- PHP
- PostgreSQL
- Docker
- Laravel Sail

## Current Features

- D&D 5e (2014) magic item compendium integration
- Equipment categories
- Item variants

## Development Setup

### Requirements

- Docker Desktop
- WSL2 with Ubuntu
- Git

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/YOUR_USERNAME/tavernsandtomes.git
   cd tavernsandtomes
   ```

2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Create your environment file:

   ```bash
   cp .env.example .env
   ```

4. Generate the application key:

   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

5. Start the containers:

   ```bash
   ./vendor/bin/sail up -d
   ```

6. Run migrations:

   ```bash
   ./vendor/bin/sail artisan migrate
   ```

## Project Goals

- D&D compendium management
- Character sheet management
- Campaign and session management
- Quest and NPC management
- Homebrew content
- AI-powered session summaries

## License

Project licensing to be determined.
