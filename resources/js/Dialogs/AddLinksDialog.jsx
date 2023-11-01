import {useEffect, useState} from "react";
import {useDebounce} from "use-debounce";
import TvDBAPI from "@/API/TvDBAPI.js";
import toast from "react-hot-toast";
import SeriesAPI from "@/API/SeriesAPI.js";
import {Box, Button, Dialog, DialogActions, DialogContent, DialogTitle, LinearProgress, TextField} from "@mui/material";
import ShowTile from "@/Components/ShowTile.jsx";
import * as Yup from "yup";
import Typography from "@mui/material/Typography";

function LinearProgressWithLabel(props) {
    return (
        <Box sx={{ display: 'flex', alignItems: 'center' }}>
            <Box sx={{ width: '100%', mr: 1 }}>
                <LinearProgress variant="determinate" {...props} />
            </Box>
            <Box sx={{ minWidth: 35 }}>
                <Typography variant="body2" color="text.secondary">{`${Math.round(
                    props.value,
                )}%`}</Typography>
            </Box>
        </Box>
    );
}


const AddLinksDialog = ({open,onClose,progress = false,progressValue = 0,showType='series'}) => {

    const [links,setLinks] = useState('');

    const handleClose = async  () => {

        let toReturn = [

        ]

        //split links by new line
        const linksArray = links.split('\n');
        for(let link of linksArray){
            //try{

                if(link.length === 0) continue;
                if(showType == 'movie'){
                    toReturn = [...toReturn,link]
                    continue;
                }


                const res = link;
                //search inside res for SXXEYY pattern
                const regex = /[Ss]\d{2}[Ee]\d{2}/g;
                const match = res.match(regex);
                if(!match) continue;

                const [s,e] = match[0].substring(1,match[0].length).split('E');

                const seasonNumber = parseInt(s);
                const episodeNumber = parseInt(e);

                if(toReturn.filter((season) =>  season?.season == seasonNumber).length === 0){
                    toReturn = [...toReturn,{
                        season: seasonNumber,
                        episodes: []
                    }];
                }

                toReturn = toReturn.map((season) => {
                    if(season.season == seasonNumber){
                        if(!season.episodes.find((episode) => episode.episode == episodeNumber))
                            season.episodes.push({
                                episode: episodeNumber,
                                links: []
                            })
                        return {
                            ...season,
                            episodes: season.episodes.map((episode) => {
                                if (episode.episode == episodeNumber) {
                                    return {
                                        ...episode,
                                        links: [
                                            ...episode.links,
                                            res
                                        ]
                                    }
                                }
                                return episode;
                            })
                        }
                    }
                    return season;
                });

        }
        onClose(toReturn);
    }

    return <Dialog
        open={open}
        onClose={onClose}
        fullWidth={true}
        maxWidth={'md'}
    >
        <DialogTitle>
            {"Wprowadź linki"}
        </DialogTitle>
        {
            progress && <LinearProgressWithLabel value={progressValue} />
        }
        <DialogContent >
            <div style={{height: '10px'}} ></div>
            <TextField
                fullWidth={true}
                multiline={true}
                rows={10}
                label={'Linki'}
                value={links}
                onChange={(e) => setLinks(e.target.value)}
            />
        </DialogContent>
        <DialogActions>
            <Button onClick={handleClose} autoFocus>
                Wczytaj
            </Button>
        </DialogActions>
    </Dialog>

}

export default AddLinksDialog;
