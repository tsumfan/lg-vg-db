require "nokogiri"
require "porter-stemmer"

module Jekyll
    class SearchIndexTerms < Liquid::Block
        @@current_doc = 0
        @@docs = {}
  
        def initialize(tag_name, text, tokens)
            super
            @stopwords = ['a', 'and', 'the', 'them', 'they',
                'their', 'theyre', 'at', 'it', 'was', 'what',
                'dont', 'did', 'or', 'on', 'that', 'each', 'to', 'no', 'not', 'this', 'of'];
            @stemmer = ::Porter::Stemmer.new
        end
  
        def render(context)
            text = super
            if text == "final"
                index = {}
                @@docs.each do |doc,terms|
                    terms.each do |term|
                        if index[term] == nil
                            index[term] = []
                        end
                        index[term].append(doc.to_i)
                    end
                end
                #compose index here.
                @@docs = {}
                @@current_doc = 0
                return index.to_json
            end
            @@current_doc += 1
            @@docs[@@current_doc.to_s] = index_terms(Nokogiri::HTML(text).text.strip)
            @@current_doc.to_s
        end

        def index_terms(str)
            words = str.downcase.sub(/["']/,"").split(/[^\w0-9]+/) - @stopwords
            words.map!{ |x| @stemmer.stem(x) }
            words = words.uniq
            words.select{ |x| x.match(/^[0-9]+$/) == nil }
        end
    end
end



Liquid::Template.register_tag('search_terms', Jekyll::SearchIndexTerms)