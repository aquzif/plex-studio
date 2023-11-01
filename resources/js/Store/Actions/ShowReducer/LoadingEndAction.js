
const showLoadEnd = (state, action) => {

    let data = action.payload;


    data = data.map((show) => {

        if(show.type === 'movie')
            return show;

        let first_release = '';
        let last_release = '';

        show.seasons.map((season) => {
            if(season.season_order_number == '0') return;
            season.episodes.map((episode) => {
                if(episode.release_date !== null){
                    if(first_release === '' || episode.release_date < first_release)
                        first_release = episode.release_date;
                    if(last_release === '' || episode.release_date > last_release)
                        last_release = episode.release_date;
                }
            });
        });

        return {
            ...show,
            first_release,
            last_release
        };

    });

    return {
        ...state,
        data,
        isLoading: false
    }
}

export default showLoadEnd;
