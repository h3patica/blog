require 'rss'
require 'sqlite3'
require 'date'

class GenRss
    def initialize
        db = SQLite3::Database.open 'posts.db'
        posts = db.query "SELECT * FROM tbl1 WHERE tags LIKE '%blog%' ORDER BY date DESC"

        rss = RSS::Maker.make("atom") do |maker|
            maker.channel.author = "hepatica"
            maker.channel.updated = Time.now.to_s
            maker.channel.about = "https://krissy.club"
            maker.channel.title = "Hepatiki"

        	posts.each_hash { |post|
                maker.items.new_item do |item|
                  item.link = "https://krissy.club/?date=#{post['date']}"
                  item.title = post["date"]
                  item.updated = Time.at(post["date"]).to_s
                  item.description = post["content"]
                end
            }
        end
        File.open("rss.xml", 'w') { |file| file.write(rss) }
    end
end
