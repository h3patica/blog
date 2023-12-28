require 'discordrb'
require 'sqlite3'
require_relative 'gen_rss'

bot = Discordrb::Bot.new token: File.read('../token.txt').strip
db = SQLite3::Database.open 'posts.db'

bot.message do |message|
  if message.content[0] == '+'
    lines = message.content.split $/
    tags = lines[1]
    content = lines[2..].join $/
    date = Time.now.to_i.to_s
    db.execute "INSERT INTO tbl1 VALUES (?, ?, ?)", content, tags, date
    message.respond date
  end
  if message.content[0] == '-'
    lines = message.content.split $/
    date = lines[1]
    db.execute "DELETE FROM tbl1 WHERE date=?", date
    message.respond date
  end
  GenRss.new
end

bot.run
