import {useParams} from "react-router-dom";
import {useSelector} from "react-redux";
import Typography from "@mui/material/Typography";
import {
    Autocomplete, Chip, FormControlLabel, FormGroup,
    IconButton,
    Paper, Switch,
    Table,
    TableBody,
    TableCell,
    TableContainer,
    TableHead,
    TableRow, TextField,
    Tooltip,

} from "@mui/material";
import {alpha} from "@mui/material/styles";
import Toolbar from "@mui/material/Toolbar";
import {
    Add,
    CheckCircleOutline,
    ConnectingAirports,
    Delete,
    Download,
    ErrorOutline,
    Extension
} from "@mui/icons-material";
import AddLinksDialog from "@/Dialogs/AddLinksDialog.jsx";
import {useState} from "react";
import {array} from "yup";
import URLAPI from "@/API/URLAPI.js";
import SeriesAPI from "@/API/SeriesAPI.js";
import styled from "styled-components";
import EpisodesAPI from "@/API/EpisodesAPI.js";
import toast from "react-hot-toast";
import QualityPill from "@/Components/QualityPill.jsx";
import SeasonsAPI from "@/API/SeasonsAPI.js";

const Link = styled.a`
  color: #fff;
`;

const UrlsView = () => {

    const [linksDialogOpen,setLinksDialogOpen] = useState(false);
    const [progressValue,setProgressValue] = useState(0);

    const shows = useSelector(state => state.showReducer);
    const {id,season} = useParams();
    const selectedShow = shows.data.find(show => show.id === parseInt(id));

    const settings = useSelector(state => state.settingsReducer);

    const selectedSeason = selectedShow?.seasons.find(s => s.id === parseInt(season));

    const onAddLinksClose = async (res = false) => {

        if(res?.target) {
            setLinksDialogOpen(false);
            return;
        }

        //count all links
        let allLinks = 0;
        let count = 0;

        if(selectedShow.type == 'series'){
            for(let s of res){
                for(let e of s.episodes){
                    allLinks += e.links.length;
                }
            }

            for(let s of res){

                const sea = selectedShow.seasons.filter(season => season.season_order_number == s.season)[0];

                for(let e of s.episodes){
                    const ep = sea.episodes.filter(episode => episode.episode_order_number == e.episode)[0];

                    for(let l of e.links){
                        count++;

                        setProgressValue(old =>count/allLinks*100);
                        //wait 200ms
                        //await new Promise(r => setTimeout(r, 200));
                        await URLAPI.insertURL({
                            'url': l,
                            'episode_id': ep.id
                        })

                    }
                }
            }
        }else{
            allLinks = res.length;
            for(let l of res){
                count++;
                setProgressValue(old =>count/allLinks*100);
                //wait 200ms
                //await new Promise(r => setTimeout(r, 200));
                await URLAPI.insertURL({
                    'url': l,
                    'movie_id': selectedShow.id
                })
            }
        }

        setLinksDialogOpen(false);
        SeriesAPI.refreshSeries();
    }

    const deleteLink = async (link) => {
        await URLAPI.deleteURL(link.id);
        await SeriesAPI.refreshSeries();
    }

    const toggleDownloadLink = async (link) => {

        await URLAPI.updateURL({
            id: link.id,
            url: link.url,
            downloaded: !link.downloaded
        });
        await SeriesAPI.refreshSeries();
    }

    const toggleValidLink = async (link) => {

        await URLAPI.updateURL({
            id: link.id,
            url: link.url,
            invalid: !link.invalid
        });
        await SeriesAPI.refreshSeries();
    }

    const formatReleaseDate = (date) => {

        if(!date)
            return '';

        const d = new Date(date);

        let dateDIffInDays = Math.floor((new Date() - d) / (1000 * 60 * 60 * 24));

        if(dateDIffInDays > 0){
            if(dateDIffInDays <= 7){
                if(dateDIffInDays == 0)
                    return <span style={{color: 'purple'}} >(premiera!)</span>;
                else
                 return <span style={{color: 'orange'}} >({dateDIffInDays} dni temu)</span>;
            }else{
                return `(${d.getDate()+1 < 10 && '0' || ''}${d.getDate()}.${d.getMonth()+1 < 10 && '0' || ''}${d.getMonth()+1}.${d.getFullYear()})`;
            }

        }else if (dateDIffInDays < 0){
            return <span style={{color: '#18ab21'}} >(za {Math.abs(dateDIffInDays)} dni)</span>;
        }else{
            return <span style={{color: 'purple'}} >PREMIERA</span>;
        }

        return `(${d.getDate()}.${d.getMonth()+1}.${d.getFullYear()})`;

    }

    const toggleEpisodeDownload = async (episode) => {
        if(selectedShow.type == 'series')
            await EpisodesAPI.updateEpisode({
                id: episode.id,
                downloaded: !episode.downloaded
            })
        else
            await SeriesAPI.updateShow({
                id: episode.id,
                downloaded: !episode.downloaded
            })
        await SeriesAPI.refreshSeries();
    }

    const anyDownloaded = selectedSeason?.episodes?.reduce((a,b) => a + b.downloaded ? 1:0,0) > 0;

    const toggleAllEpisodes = async () => {

        const dest = anyDownloaded ? false : true;

        const allDownloaded = selectedSeason.episodes.reduce((a,b) => a + b.downloaded ? 1:0,0) == selectedSeason.episodes.length;
        for(let episode of selectedSeason.episodes){
            await EpisodesAPI.updateEpisode({
                id: episode.id,
                downloaded: dest
            })
        }
        await SeriesAPI.refreshSeries();
    }

    const downloadByXt7 = (row) => {
        window.open(`/xt7/?url=${row.url}`, '_blank');
    }

    return <>
        <AddLinksDialog
            progress={progressValue > 0}
            showType={selectedShow?.type}
            progressValue={progressValue}
            onClose={onAddLinksClose}
            open={linksDialogOpen}
        />
        <Paper style={{padding: '20px',marginTop: '50px'}} >
            <div style={{display:'flex',flexDirection:'row',justifyContent: 'space-between'}} >
                <Typography variant="h4" component="h1" gutterBottom>
                    {selectedShow?.name} {selectedShow?.type == 'series' && ' - '} {selectedSeason?.name}
                </Typography>
                <div>
                    {
                        selectedShow?.type == 'series' &&  <IconButton
                            style={{color: anyDownloaded && '#d62929' || 'lightgreen'}}
                            onClick={() => toast.promise(toggleAllEpisodes(), {
                                loading: 'Zmieniam status wszystkich odcinków',
                                success: 'Zmieniono status wszystkich odcinków',
                                error: 'Wystąpił błąd podczas zmiany statusu wszystkich odcinków'
                            })
                            }
                        >
                            <Download />
                        </IconButton>
                    }
                    <IconButton
                        onClick={() => setLinksDialogOpen(true)}
                    >
                        <Add />
                    </IconButton>
                </div>
            </div>
            <div style={{display:'flex',flexDirection:'row',justifyContent: 'space-between'}} >
                <div style={{width: 'calc(60% - 20px)'}} >
                    <Autocomplete
                        multiple
                        id="tags-standard"
                        fullWidth={true}
                        renderTags={(value, getTagProps) =>
                            value.map((option, index) => (
                                <Chip
                                    key={index}
                                    variant="outlined"
                                    label={option.title}
                                    sx={{fontWeight: 'bold',color: option.title !== "Angielski" ? '#ffbf00' : '#8a00ff'}}
                                    {...getTagProps({ index })}
                                />
                            ))
                        }
                        options={[
                            { ord:1, title: 'Dubbing' },
                            { ord:2,title: 'Lektor' },
                            { ord:3,title: 'Angielski' },
                        ]}
                        getOptionLabel={(option) => option.title}
                        value={
                            selectedShow?.type == 'movie' ? JSON.parse(selectedShow.audio_languages) : JSON.parse(selectedSeason?.audio_languages || '[]')
                        }
                        isOptionEqualToValue={(option, value) => option.title === value.title}
                        onChange={(event, newValue) => {

                            (selectedShow?.type == 'movie' ? SeriesAPI.updateShow({
                                id: selectedShow.id,
                                audio_languages: JSON.stringify(newValue.sort(
                                    (a,b) => a.ord - b.ord
                                ))
                            }) : SeasonsAPI.updateSeason({
                                id: selectedSeason.id,
                                audio_languages: JSON.stringify(newValue.sort(
                                    (a,b) => a.ord - b.ord
                                ))
                            })).then(() => SeriesAPI.refreshSeries());


                            console.log(newValue);
                        }}
                        renderInput={(params) => (
                            <TextField
                                {...params}
                                label="Języki Audio"
                            />
                        )}
                    />
                </div>
                <div style={{width: '40%'}} >
                    <Autocomplete
                        multiple
                        id="tags-standard"
                        fullWidth={true}
                        renderTags={(value, getTagProps) =>
                                value.map((option, index) => (
                                    <Chip
                                        key={index}
                                    variant="outlined"
                                    label={option.title}
                                    sx={{fontWeight: 'bold',color: option.title === "Polski" ? '#ffbf00' : '#8a00ff'}}
                                    {...getTagProps({ index })}
                                />
                            ))
                        }
                        options={[
                            { ord:1, title: 'Polski' },
                            { ord:2, title: 'Angielski' },
                        ]}
                        getOptionLabel={(option) => option.title}

                        value={
                            selectedShow?.type == 'movie' ? JSON.parse(selectedShow.subtitle_languages) : JSON.parse(selectedSeason?.subtitle_languages || '[]')
                        }
                        isOptionEqualToValue={(option, value) => option.title === value.title}
                        onChange={(event, newValue) => {

                            (selectedShow?.type == 'movie' ? SeriesAPI.updateShow({
                                id: selectedShow.id,
                                subtitle_languages: JSON.stringify(newValue.sort(
                                    (a,b) => a.ord - b.ord
                                ))
                            }) : SeasonsAPI.updateSeason({
                                id: selectedSeason.id,
                                subtitle_languages: JSON.stringify(newValue.sort(
                                    (a,b) => a.ord - b.ord
                                ))
                            })).then(() => SeriesAPI.refreshSeries());

                        }}
                        renderInput={(params) => (<TextField{...params} label="Języki Napisów"/>)}
                    />
                </div>
            </div>
            <div style={{display:'flex',flexDirection:'row',justifyContent: 'space-between', marginTop: '20px'}} >
                <div >
                    <Autocomplete
                        getOptionLabel={(option) => option === 'undef' ? 'Nieznana' : option}
                        value={
                            selectedShow?.type == 'movie' ? selectedShow?.quality : selectedSeason?.quality || 'undef'
                        }
                        options={[
                            'undef',
                            '480p',
                            '720p',
                            '1080p',
                            '2160p',
                        ]}
                        isOptionEqualToValue={(option, value) => option === value}
                        sx={{ width: 300 }}
                        renderOption={(props, option, { selected }) => (
                            <li {...props}>
                                <span
                                    style={{
                                        color: (option === "undef" && '#878787') ||
                                            (option === "480p" && '#008900') ||
                                            (option === "720p" && '#00bfff') ||
                                            (option === "1080p" && '#8a00ff') ||
                                            (option === "2160p" && '#ffbf00') || 'white'
                                    }}
                                >{
                                    (option === "undef" && 'Nieznana') || option
                                }</span>
                            </li>
                        )}
                        onChange={(event, newValue) => {

                            (selectedShow?.type == 'movie' ? SeriesAPI.updateShow({
                                id: selectedShow.id,
                                quality: newValue
                            }) : SeasonsAPI.updateSeason({
                                id: selectedSeason.id,
                                quality: newValue
                            })).then(() => SeriesAPI.refreshSeries());

                        }}


                        renderInput={(params) =>
                            <TextField {...params}
                                       label="Jakość"
                                       sx={{ input: { fontWeight:'bold',color:
                                                   (params.inputProps.value === 'Nieznana' && '#878787') ||
                                                    (params.inputProps.value === '480p' && '#008900') ||
                                                    (params.inputProps.value === '720p' && '#00bfff') ||
                                                    (params.inputProps.value === '1080p' && '#8a00ff') ||
                                                    (params.inputProps.value === '2160p' && '#ffbf00') || 'white'
                                       } }}

                        />}
                        />
                </div>
                <div>
                    <FormGroup>
                        <FormControlLabel control={<Switch
                            checked={selectedShow?.type == 'movie' ? selectedShow?.needs_update : selectedSeason?.needs_update || false}
                            onChange={(e) => {
                                (selectedShow?.type == 'movie' ? SeriesAPI.updateShow({
                                    id: selectedShow.id,
                                    needs_update: e.target.checked
                                }) : SeasonsAPI.updateSeason({
                                    id: selectedSeason.id,
                                    needs_update: e.target.checked
                                })).then(() => SeriesAPI.refreshSeries());
                            }}
                        />} labelPlacement={'start'} label="Wymagana poprawa" />
                    </FormGroup>
                </div>
            </div>
        </Paper>

        {
            (
                selectedShow?.type == 'movie' ? [selectedShow] : selectedSeason?.episodes?.filter(episode => !settings.hideDownloadedEpisodes || !episode.downloaded ) || []
            ).map(episode => (<>
                <TableContainer component={Paper}
                                key={episode.id}
                    sx={{margin: '20px 2px'}}
                >
                    <Toolbar
                        sx={{
                            pl: { sm: 2 },
                            pr: { xs: 1, sm: 1 },
                            ...(0 > 0 && {
                                bgcolor: (theme) =>
                                    alpha(theme.palette.primary.main, theme.palette.action.activatedOpacity),
                            }),
                        }}
                    >
                        <Typography
                            sx={{ flex: '1 1 100%' }}
                            variant="h6"
                            id="tableTitle"
                            component="div"
                            style={{
                                color: episode.downloaded && 'lightgreen' || 'white'
                            }}
                        >
                            {
                                selectedShow?.type == 'movie' ? 'Lista linków' : `Odcinek ${episode.episode_order_number} - ${episode.name} `
                            }
                            {
                                formatReleaseDate(episode.release_date)
                            }
                        </Typography>
                        <Tooltip title={`Oznacz jako ${episode.downloaded && 'nie' || ''}pobrany`}>
                            <IconButton
                                style={{color: episode.downloaded && '#d62929' || 'lightgreen'}}
                                onClick={() => toggleEpisodeDownload(episode)}
                            >
                                <Download />
                            </IconButton>
                        </Tooltip>
                    </Toolbar>
                    <Table sx={{ minWidth: 650 }} aria-label="simple table" size="small">
                        <TableHead>
                            <TableRow>
                                <TableCell></TableCell>
                                <TableCell>Link</TableCell>
                                <TableCell>Quality</TableCell>
                                <TableCell align="right">Tools</TableCell>
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {episode.urls.map((row) => (
                                <TableRow
                                    key={row.name}
                                    sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
                                >
                                    <TableCell component="th" scope="row">
                                        <Tooltip title={
                                            <span>Ostatnie sprawdzenie: <br/> {new Date(row.last_validated_date).toLocaleDateString()} {new Date(row.last_validated_date).toLocaleTimeString()}</span>
                                        }>
                                            <CheckCircleOutline
                                                sx={{color: row.auto_valid ? 'lightGreen' : 'red'}}
                                            />
                                        </Tooltip>
                                    </TableCell>
                                    <TableCell component="th" scope="row">

                                        <Link
                                            style={{wordBreak: 'break-all',
                                                color: row.invalid ? 'red' : (row.downloaded ? 'lightGreen' : 'white')
                                            }}
                                            target={'_blank'} href={row.url} >{row.url}</Link>
                                    </TableCell>
                                    <TableCell>
                                        <QualityPill quality={row.quality} />
                                    </TableCell>
                                    <TableCell align="right">
                                        <div style={{display: 'flex',flexDirection: 'row',justifyContent:'right'}} >
                                            <IconButton
                                                sx={{color: '#f9651f'}}
                                                onClick={() => downloadByXt7(row)}
                                            >
                                                <ConnectingAirports />
                                            </IconButton>
                                            <IconButton
                                                sx={{color: 'yellow'}}
                                                onClick={() => toggleValidLink(row)}
                                            >
                                                <ErrorOutline />
                                            </IconButton>
                                            <IconButton
                                                sx={{color: row.downloaded ? '#d62929':'lightGreen'}}
                                                onClick={() => toggleDownloadLink(row)}
                                            >
                                                <Download />
                                            </IconButton>
                                            <IconButton
                                                sx={{color: '#d62929'}}
                                                onClick={() => deleteLink(row)}
                                            >
                                                <Delete />
                                            </IconButton>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                </TableContainer>
            </>))
        }
    </>
}

export default UrlsView;
