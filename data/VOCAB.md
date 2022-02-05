

Platform:
* emulators: Thing
* properties of Thing
* series: Game or Series inverse of *.genre

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
* name: name of object
* description: Optional description
* link: Links to outside sources

Link:
* name: Link name
* description: Link description
* url: Link URL

DefinitionList:
* entry: Thing (any thing we want)