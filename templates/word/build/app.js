require(["main"], function(Soup){
    Soup.points = {
        ON_FOUND: 10,
        ON_HINT: -10
    };

    var soup = new Soup({
        totalWords: 10,
        size: 20, // 15x15
        every: 3, // 10 seconds
        deduct: 2
    });
});
