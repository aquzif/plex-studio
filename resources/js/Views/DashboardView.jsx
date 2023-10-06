import {Outlet, useMatch, useMatches, useNavigate} from "react-router-dom";
import {AppBar, Box, IconButton, Tooltip} from "@mui/material";
import Container from "@mui/material/Container";
import Toolbar from "@mui/material/Toolbar";
import VideoLibraryIcon from '@mui/icons-material/VideoLibrary';
import Typography from "@mui/material/Typography";
import * as React from "react";
import AddNewSeriesDialog from "@/Dialogs/AddNewSeriesDialog.jsx";
import {logout} from "@/Store/Reducers/AuthReducer.js";
import store from "@/Store/store.js";
import {ArrowUpward, Logout, Settings} from "@mui/icons-material";
import {useEffect} from "react";
import SeriesAPI from "@/API/SeriesAPI.js";
import { styled, alpha } from '@mui/material/styles';
import {endLoading, loadShows} from "@/Store/Reducers/ShowReducer.js";
import SearchIcon from '@mui/icons-material/Search';
import InputBase from '@mui/material/InputBase';
import {searchbarUpdate} from "@/Store/Reducers/SearchBarReducer.js";
import SettingsDialog from "@/Dialogs/SettingsDialog.jsx";


const Search = styled('div')(({ theme }) => ({
    position: 'relative',
    borderRadius: theme.shape.borderRadius,
    backgroundColor: alpha(theme.palette.common.white, 0.15),
    '&:hover': {
        backgroundColor: alpha(theme.palette.common.white, 0.25),
    },
    marginRight: theme.spacing(2),
    marginLeft: 0,
    width: '100%',
    [theme.breakpoints.up('sm')]: {
        marginLeft: '10px',
        width: 'auto',
    },
}));

const SearchIconWrapper = styled('div')(({ theme }) => ({
    padding: theme.spacing(0, 2),
    height: '100%',
    position: 'absolute',
    pointerEvents: 'none',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
}));

const StyledInputBase = styled(InputBase)(({ theme }) => ({
    color: 'inherit',
    '& .MuiInputBase-input': {
        padding: theme.spacing(1, 1, 1, 0),
        // vertical padding + font size from searchIcon
        paddingLeft: `calc(1em + ${theme.spacing(4)})`,
        transition: theme.transitions.create('width'),
        width: '100%',
        [theme.breakpoints.up('md')]: {
            width: '100ch',
        },
    },
}));

const LogoImage = styled('img')(({ theme }) => ({
    width: '32',
    height: '32',
    marginRight: '10px'
}));

const DashboardView = () => {

    const [searchValue,setSearchValue] = React.useState('');
    const [settingsOpen,setSettingsOpen] = React.useState(false);
    const matchesShow = useMatch('/show/:id');
    const matchesSeasons = useMatch('/show/:id/season/:season');
    const matchesEpisodes = useMatch('/show/:id/season/:season/episode/:episode');

    const navigate = useNavigate();

    const handleLogout = () => {
        store.dispatch(logout());
    }
    useEffect(() => {
        SeriesAPI.refreshSeries();
    }, []);

    useEffect(() => {
        store.dispatch(searchbarUpdate(searchValue));
    }, [searchValue])

    const handleGoUp = () => {
        if(matchesSeasons)
            navigate(`/show/${matchesSeasons.params.id}`);
        else if(matchesEpisodes)
            navigate(`/show/${matchesEpisodes.params.id}/season/${matchesEpisodes.params.season}`);
        else if(matchesShow)
            navigate('/');

    }

    return (
        <>
            <SettingsDialog
                open={settingsOpen}
                onClose={() => setSettingsOpen(false)}
            />
            <Box sx={{ flexGrow: 1 }}>
                <AppBar position="static">
                    <Toolbar >
                        <div style={{
                            display: 'flex',
                            flexDirection: 'row',
                            width: '200px'
                        }} >
                            {/*<VideoLibraryIcon sx={{ display: { xs: 'none', md: 'flex' }, mr: 1 }} />*/}
                            <LogoImage src={'/favicon.png'} />
                            <Typography
                                variant="h6"
                                noWrap
                                href={'#'}
                                component="a"
                                onClick={(e) => {
                                    e.preventDefault();
                                    navigate('/');
                                }}
                                sx={{
                                    mr: 2,
                                    display: { xs: 'none', md: 'flex' },
                                    fontFamily: 'monospace',
                                    fontWeight: 700,
                                    letterSpacing: '.1rem',
                                    color: 'inherit',
                                    textDecoration: 'none',

                                }}
                            >
                                Plex Studio
                            </Typography>
                        </div>
                        <div style={{flexGrow: 1}} ></div>
                        {
                            (matchesSeasons || matchesEpisodes || matchesShow) && <IconButton
                                size="large"
                                aria-label="account of current user"
                                aria-controls="menu-appbar"
                                aria-haspopup="true"
                                onClick={handleGoUp}
                                color="inherit"
                            >
                                <ArrowUpward />
                            </IconButton>
                        }

                        <Search>
                            <SearchIconWrapper>
                                <SearchIcon />
                            </SearchIconWrapper>
                            <StyledInputBase
                                value={searchValue}
                                onChange={(e) => setSearchValue(e.target.value)}
                                placeholder="Search…"
                                inputProps={{ 'aria-label': 'search' }}
                            />
                        </Search>
                        <div style={{flexGrow: 1}} ></div>
                        <div style={{width: '200px',
                            display: 'flex',
                            flexDirection: 'row',
                            justifyContent: 'right'
                        }}>
                            <Tooltip title={'Ustawienia'} arrow>
                                <IconButton
                                    size="large"
                                    aria-label="account of current user"
                                    aria-controls="menu-appbar"
                                    aria-haspopup="true"
                                    onClick={() => setSettingsOpen(true)}
                                    color="inherit"
                                >
                                    <Settings />
                                </IconButton>
                            </Tooltip>
                            <Tooltip title={'Wyloguj'} arrow>
                                <IconButton
                                    size="large"
                                    aria-label="account of current user"
                                    aria-controls="menu-appbar"
                                    aria-haspopup="true"
                                    onClick={handleLogout}
                                    color="inherit"
                                >
                                    <Logout />
                                </IconButton>
                            </Tooltip>
                        </div>
                    </Toolbar>
                </AppBar>
            </Box>
            <Container maxWidth="md">
                <Outlet />
            </Container>
        </>
    )
}

export default DashboardView;
