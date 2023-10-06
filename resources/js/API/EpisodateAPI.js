
export default class EpisodateAPI{

    static searchForSeries = query => {
        return fetch(`https://www.episodate.com/api/search?q=${query}`)
        .then(response => response.json())
        .then(data => data.tv_shows)
    }

}
