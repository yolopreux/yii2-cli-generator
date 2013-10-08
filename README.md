yii2-cli-generator
==================

Generate active record model using console command.

---
SUB-COMMANDS

- generate/index (default): The default sub-command action
- generate/model: Generate model
- generate/models: Generate models for all db tables

To see the detailed information about individual sub-commands, enter:

  yii help <sub-command>

---
Generate model

USAGE

yii generate/model <table_name> <model_class> [skip_on_update] [...options...]

- table_name (required): string
  The db table name

- model_class (required): string
  The model class name

- skip_on_update: boolean (defaults to 1)
  The flag indicates overwrite model class, base class is overwiten always

---
Generate models for all db tables. The class name is clasifies by table name.

USAGE

yii generate/models [skip_on_update] [...options...]

- skip_on_update: string (defaults to 1)
  The flag indicates overwrite model class, base class is overwiten always.
