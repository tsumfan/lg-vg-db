

Platform:
* emulators: Thing
* properties of Thing
* game: Game or Series inverse of *.platform

Genre:
* properties of Thing
* game: Game or Series inverse of *.genre

Series:
* platform: Platform(s)
* genre: Genres(s)
* properties of Thing
* game: Game(s) inverse of Game.series

Game:
* platform: Platform(s)
* genre: Genres(s)
* series: Series - inverse of Series.game
* publisher: Thing
* properties of Thing

Thing:
* thumbnail: image(s)
* altName: Alternative name(s) of the the entry
* name: name of object
* description: Optional description
* link: Links to outside sources
* image: image(s)

Image:
* name: image filename
* caption: caption string

Link:
* name: Link name
* description: Link description
* url: Link URL

DefinitionList:
* entry: Thing (any thing we want)