import React, {useEffect, useState} from "react";
import SeriesAPI from "@/API/SeriesAPI.js";
import {Fab, Tab, Tabs, Tooltip} from "@mui/material";
import {Add} from "@mui/icons-material";
import AddNewSeriesDialog from "@/Dialogs/AddNewSeriesDialog.jsx";
import {useSelector} from "react-redux";
import showReducer from "@/Store/Reducers/ShowReducer.js";
import ShowTile from "@/Components/ShowTile.jsx";
import SearchUtils from "@/Utils/SearchUtils.js";
import {useNavigate} from "react-router-dom";
import TileTooltip from "@/Components/TileTooltip.jsx";
import toast from "react-hot-toast";
import moment from "moment";

const getShowCompletionPercentage = (show) => {
    let total = show.seasons.filter(s=>parseInt(s.season_order_number)).reduce((acc,season) => {
        return acc + season.episodes.length;
    },0);
    let downloaded = show.seasons.filter(s=>parseInt(s.season_order_number)).reduce((acc,season) => {
        return acc + season.episodes.filter((episode) => episode.downloaded).length;
    },0);

    if(show.type === 'movie'){
        if(show.downloaded){
            total = 1;
            downloaded = 1;
        }else if(show.urls.length === 0 ){
            total = 1;
            downloaded = 0;
        }else{
            total = show.urls.length;
            downloaded = show.urls.filter((url) => url.downloaded).length;
        }


    }


    return Math.round(downloaded/total * 100);
}

const SeriesList = () => {

    const [data,setData] = useState([]);
    const [addNewSeriesDialogOpen,setAddNewSeriesDialogOpen] = React.useState(false);

    const shows = useSelector(state => state.showReducer);
    const searchValue = useSelector(state => state.searchbarReducer);
    const settings = useSelector(state => state.settingsReducer);

    const [tab,setTab] = useState(0);

    const navigate = useNavigate();

    const prepareDataToShow = () => {
        let data = shows.data.map((show) => {
          return {
              ...show,
              completion: getShowCompletionPercentage(show)
          }
        });

        if(settings.showOnlyNotCompleted){
            data = data.filter((show) => {
                return show.completion !== 100;
            });
        }
        if(settings.showOnlyFavorites){
            data = data.filter((show) => {
                return show.favourite;
            });
        }

        let sortDirection = settings.sortDirection === 'asc' ? 1 : -1;

        if(settings.sortBy === 'name'){
            data = data.sort((a,b) => {
                return a.name.localeCompare(b.name) * sortDirection;
            });
        }
        if(settings.sortBy === "complete"){
            data = data.sort((a,b) => {
                return (a.completion - b.completion) * sortDirection;
            });
        }
        if(settings.sortBy === 'first_release'){
            let movies = data.filter((show) => show.type === 'movie');

            data = data.filter((show) => show.type !== 'movie')
                .sort((a,b) => {
                let datea = moment(a.first_release)
                let dateb = moment(b.first_release)

                //if date is in future, set it to now
                if(datea.isAfter(moment())) datea = moment();
                if(dateb.isAfter(moment())) dateb = moment();

                return (datea > dateb ? 1 : -1) * sortDirection;
            });

            data = [...data,...movies];
        }

        if(settings.sortBy === 'last_release'){
            let movies = data.filter((show) => show.type === 'movie');

            data = data.filter((show) => show.type !== 'movie')
                .sort((a,b) => {

                let datea = moment(a.last_release)
                let dateb = moment(b.last_release)

                //if date is in future, set it to now
                if(datea.isAfter(moment())) datea = moment();
                if(dateb.isAfter(moment())) dateb = moment();

                return (datea > dateb ? 1 : -1) * sortDirection;
            });

            data = [...data,...movies];
        }

        data = SearchUtils.advancedSearch(data || [],searchValue);
        return data;
    }

    const onCloseDialog = (added) => {
        setAddNewSeriesDialogOpen(false);
        if(added)
            SeriesAPI.refreshSeries();
    }

    const addNewSeries = () => {
        setAddNewSeriesDialogOpen(true);
    }

    const onSelectShow = async (id) => {

        let res = shows.data.filter((show) => show.id === id)[0];

        navigate(`/show/${res.id}`);

    }

    const onFavoriteClick = async (id) => {

        const show = shows.data.filter((show) => show.id === id)[0];
        await SeriesAPI.updateShow({
            'id': show.id,
            'favourite': !show.favourite
        });
        await SeriesAPI.refreshSeries();

    }

    const handleDeleteShow = async (id) => {
        const show = shows.data.filter((show) => show.id === id)[0];
        if(!confirm(`Czy na pewno chcesz usunąć "${show.name}"?`)) return;

        await toast.promise(SeriesAPI.deleteShow(id),{
            loading: 'Usuwanie...',
            success: 'Usunięto',
            error: 'Błąd podczas usuwania'
        })
        await SeriesAPI.refreshSeries();

    }

    const changeTab = (event,value) => {
        setTab(value);
    }

    return <>
        <AddNewSeriesDialog open={addNewSeriesDialogOpen} onClose={onCloseDialog} />
        <Fab color="primary"
             onClick={addNewSeries}
             aria-label="add"
             style={{position: 'fixed', bottom: '20px', right: '20px'}}
        >
            <Add />
        </Fab>
        <div
            style={{display: 'flex', justifyContent: 'center'}}
        >
            <div style={{width: '100px'}} />
            <Tabs value={tab} onChange={changeTab} centered >
                <Tab label="Seriale" value={0}  />
                <Tab label="Filmy" value={1}  />
                {/*<Tab label="Item Three" value={2} />*/}
            </Tabs>
            <div style={{width: '100px'}} />
        </div>
        <div style={{display: 'flex', flexWrap: 'wrap', justifyContent: 'space-between'}}>
            {
                prepareDataToShow().filter(s => (
                    tab === 0 && s.type === 'series') || (tab === 1 && s.type === 'movie')
                ).map((show,index) => {

                    let completion = getShowCompletionPercentage(show);

                    return <TileTooltip
                        key={show.id}
                        name={show.name}
                    >
                       <ShowTile
                           text={completion + '%'}
                            borderColor={completion === 100 ? 'green' :
                                completion > 0 ? 'orange' : 'red'}
                           showBorder={true}
                           onFavoriteClick={onFavoriteClick}
                           isFavorite={show.favourite}
                           name={show.name}
                           id={show.id}
                           onDeleteClick={handleDeleteShow}
                           onClick={onSelectShow}
                           thumbnail={show.thumb_path}
                       />
                    </TileTooltip>
                })
            }
        </div>
    </>

}

export default SeriesList;
