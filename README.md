![image](https://github.com/h3patica/blog/assets/73669382/576f2908-0776-4fa1-b5eb-a4ddff7ab36c)
Hosted at https://krissy.club
# Requirements
## PHP Server
- PHP — `apt install php`
- SQLite3 — `apt install sqlite3`
- PHP SQLite3 module — `apt install php-sqlite3`

## Discord Bot
- Ruby —  `apt install ruby`
- SQLite Ruby gem — `apt install ruby-sqlite3` / `apt install libsqlite3-dev` + `gem install sqlite3`
- [Discordrb](https://github.com/shardlab/discordrb) — `gem install discordrb` (may also require Ruby dev librarires)

# Organization
The SQLite3 database `./posts.db` is based off of a denormalized “MySQLicious” style solution to tagging. The sole table `tbl1` looks like this:
| content            | tags     | date       |
|--------------------|----------|------------|
| hello world        | blog     | 1702771451 |
| testing new things | blog dev | 1702795393 |

The Discord bot written in Ruby acts as a client to communicate with the PHP server indirectly. Commands are written to a server with it like such:

Creating a blog post
```
+              ex | +
tags              | blog dev
content           | testing new things
```
or removing one
```
-              ex | -
date              | 1702795393
```
