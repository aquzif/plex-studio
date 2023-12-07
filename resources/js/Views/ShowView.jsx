import {useSelector} from "react-redux";
import {useNavigate, useParams} from "react-router-dom";
import SearchUtils from "@/Utils/SearchUtils.js";
import ShowTile from "@/Components/ShowTile.jsx";
import React from "react";
import UrlsView from "@/Views/UrlsView.jsx";
import TileTooltip from "@/Components/TileTooltip.jsx";

const downloaded = season => {
    return season.episodes.filter(episode => episode.downloaded === true);
}

const getSeasonColor = season => {
    if (season.episodes.length === downloaded(season).length) {
        return 'green';
    } else if (downloaded(season).length === 0) {
        return 'red';
    } else {
        return 'orange';
    }
}


const ShowView = () => {

    const {id} = useParams();

    const searchValue = useSelector(state => state.searchbarReducer);
    const shows = useSelector(state => state.showReducer);
    const navigate = useNavigate();

    const selectedShow = shows.data.find(show => show.id === parseInt(id));

    const onSelectSeries = (seasonId) => {
        navigate('/show/' + id + '/season/' + seasonId);
    }

    if(selectedShow?.type === 'movie'){
        return (
            <UrlsView />
        )
    }else{
        return (
            <div style={{display: 'flex', flexWrap: 'wrap', justifyContent: 'space-around'}}>
                {
                    (selectedShow?.seasons || []).map((show,index) => {
                        return <TileTooltip
                            key={show.id}
                            name={show.name}
                        ><ShowTile key={show.id}
                                         name={show.name}
                                         showBorder={true}
                                         id={show.id}
                                         text={downloaded(show).length + '/' + show.episodes.length}
                                         borderColor={getSeasonColor(show)}
                                         onClick={onSelectSeries}
                                         thumbnail={show.thumb_path}
                        />
                        </TileTooltip>
                    })
                }
            </div>
        )
    }
}

export default ShowView;

