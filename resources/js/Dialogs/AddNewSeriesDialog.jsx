import {
    Button,
    Dialog,
    DialogActions,
    DialogContent,
    DialogContentText,
    DialogTitle, Paper, Tab, Table, TableBody, TableCell, TableHead, TableRow,
    Tabs,
    TextField
} from "@mui/material";
import {useEffect, useState} from "react";
import {useDebounce} from "use-debounce";
import ShowTile from "@/Components/ShowTile.jsx";
import TvDBAPI from "@/API/TvDBAPI.js";
import SeriesAPI from "@/API/SeriesAPI.js";
import toast from "react-hot-toast";
import TileTooltip from "@/Components/TileTooltip.jsx";
import {styled} from "@mui/material/styles";

const StyledTableRow = styled(TableRow)(({ theme }) => ({
    '&:nth-of-type(odd)': {
        backgroundColor: "#252525",
    },
    // '&:nth-of-type(even)': {
    //     backgroundColor: "#202020",
    // },
}));

const AddNewSeriesDialog = ({open,onClose}) => {

    const [search,setSearch] = useState('');
    const [results,setResults] = useState([]);
    const [selectedShow,setSelectedShow] = useState(null);
    const [selectedSeasonType,setSelectedSeasonType] = useState(null);
    const [seasonsTypes,setSeasonsTypes] = useState([]);
    const [searchVal] = useDebounce(search,500);

    useEffect(() => {

        if(searchVal.length < 3) return;
        setResults([]);
        TvDBAPI.searchForShows(searchVal).then((res) => {
            setResults(res)
        });

    }, [searchVal]);

    const reset = () =>{
        setSelectedShow(null);
        setSearch('');
        setResults([]);
        setSeasonsTypes([]);
    }

    useEffect(() => {
        if(selectedShow == null) {
            setSeasonsTypes([]);
            return;
        }

        TvDBAPI.getSeasonsTypes(selectedShow.id.split('-')[1]).then((res) => {

            setSelectedSeasonType(res[0]['type']);
            setSeasonsTypes(res.map((item) => {
                            return {
                                ...item,
                                seasons: Object.assign({}, item.seasons)
                            }
                        }));
        });

    }, [selectedShow]);



    const onSelectShow = async (id) => {
        let res = results[id];
        let tvdb_id = res.id.split('-')[1];

        //check if already added
        let exists = await SeriesAPI.checkIfAdded(tvdb_id);

        if(exists){
            toast.error('Serial już istnieje');
            return;
        }

        if(res.type === 'series'){
            setSelectedShow(res);

            return;
        }


        await toast.promise(SeriesAPI.addNewShow({
            type: res.type,
            tvdb_id
        }),{
            loading: 'Dodawanie filmu',
            success: () => {
                handleClose(true);
                return 'Serial dodany';
            },
            error: 'Nie udało się dodać filmu'
        });

    }

    const handleClose = (res) => {
        reset();
        onClose(res);
    }

    const finalizeSeries = async () => {
        let tvdb_id = selectedShow.id.split('-')[1];
        await toast.promise(SeriesAPI.addNewShow({
            type: selectedShow.type,
            tvdb_id,
            seasons_type: selectedSeasonType
        }),{
            loading: 'Dodawanie serialu',
            success: () => {
                handleClose(true);
                return 'Serial dodany';
            },
            error: 'Nie udało się dodać filmu'
        });
    }

    const handleTabChange = (e,tab) => {
        setSelectedSeasonType(tab);
    }

    return <Dialog
        open={open}
        onClose={handleClose}

        fullWidth={true}
        maxWidth={'md'}
    >
        <DialogTitle>
            {
                selectedShow == null ? 'Wyszukaj show' : (selectedShow.extended_title || selectedShow.name)+' - Wybierz serializację'
            }
        </DialogTitle>
        <DialogContent
            sx={{height: '530px'}}
        >
            {
                selectedShow == null ? <>
                    <TextField
                        variant={'standard'}
                        fullWidth={true}
                        label={'Wyszukaj serial'}
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                    />
                    <div style={{display: 'flex', flexWrap: 'wrap', justifyContent: 'space-around'}}>
                        {
                            results.length >0 && results.map((item,index) => {
                                return <TileTooltip
                                    key={index}
                                    name={item.extended_title || item.name}

                                ><ShowTile
                                    onClick={onSelectShow}
                                    name={item.extended_title || item.name}
                                    id={index}
                                    thumbnail={item.image_url}
                                />
                                </TileTooltip>
                            })
                        }
                    </div>
                </> : <>
                    <div
                        style={{display: 'flex', justifyContent: 'space-between'}}
                    >
                        <div />
                        <Tabs value={selectedSeasonType} onChange={handleTabChange} centered >
                            {
                                seasonsTypes.map((item,index) => {
                                    return <Tab label={item.name} value={item.type} key={index} />
                                })
                            }

                        </Tabs>
                        <div />
                    </div>
                    <Paper sx={{marginTop:'20px'}} >
                        <Table size="small">
                            <TableHead>
                                <TableRow>
                                    <TableCell>Sezon</TableCell>
                                    <TableCell align={'right'} >Odcinki</TableCell>
                                </TableRow>
                            </TableHead>
                            <TableBody>
                                {
                                    seasonsTypes !== [] && selectedSeasonType && Object.keys(seasonsTypes.filter((item) => item.type === selectedSeasonType)[0]?.seasons).map((item,index) => {
                                        return <StyledTableRow key={index}>
                                            <TableCell>{item}</TableCell>
                                            <TableCell align={'right'}>{seasonsTypes.filter((item) => item.type === selectedSeasonType)[0].seasons[item]}</TableCell>
                                        </StyledTableRow>
                                    })
                                }
                            </TableBody>
                        </Table>
                    </Paper>
                </>
            }
        </DialogContent>
        {
            seasonsTypes !== [] && selectedSeasonType && <DialogActions>
                <Button onClick={finalizeSeries} autoFocus>
                    Zatwierdź
                </Button>
            </DialogActions>
        }

    </Dialog>

}

export default AddNewSeriesDialog;
