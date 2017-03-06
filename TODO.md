**Todo**

1. Find a way to handle GET / UPDATE / DELETE methods like `$ artifakt-cli get hello 1 // GET /api/hellos/1` ?
2. Find a way to handle CREATE / UPDATE parameters :
    - Interactive commands?
    - Raw JSON?
    - Array params?
    - Other?
3. Move logic outside of the `ArtifaktCommand`
4. Config/Mapping for Entities/Actions? :
    - YML :
        ```
        # some/config/file.yml
        
        entities:
            hello:
                methods: ['get', 'list', 'create', 'update', 'delete']
        ```
    - PHP : ?
    - XML : ?
    - Annotation : ¯\\\_(ツ)_/¯
5. Improve documentation
6. Unit tests