onmessage = function(e) {
    var endpoint = e.data[0];
    var results = e.data[1];
    console.log(e.data);

    $.post( endpoint, results, function( data ) {
        //noinspection JSUnresolvedFunction
        postMessage(data);
    });
};
