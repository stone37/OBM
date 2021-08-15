const url = new URL('https://localhost/.well-known/mercure');
//url.searchParams.append('topic', 'http://monsite/ping');
url.searchParams.append('topic', '/notifications/{channel}')
url.searchParams.append('topic', '/notifications/user/1')

const eventSource = new EventSource(url);

eventSource.onmessage = e => {
    console.log(e);
};

window.addEventListener('beforeunload', function () {
    if (eventSource != null) {
        eventSource.close();
    }
})

