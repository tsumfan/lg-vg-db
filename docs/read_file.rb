require 'yaml'

if !ARGV[0] or !File.exists?(ARGV[0])
  print("Need file\n");
  exit;
end

file = File.open(ARGV[0])
contents = file.read

if (md = contents.match(/^(?<metadata>---\s*\n.*?\n?)^(---\s*$\n?)/m))
  contents = md.post_match
  metadata = YAML.load(md[:metadata])
  desc = contents.strip
  if !(desc == "") and metadata.has_key?("video")
    exit;
  end;
  
  print(metadata["title"])
  print("\n")
  if desc == ""
    print("Description:\n\nDescription Credit Link:\n\nLinks:\n\n");
  end
  if !metadata.has_key?("video")
    print("Video:\n\n");
  end
  print("\n")
  print("----------------------------------------\n")
end

