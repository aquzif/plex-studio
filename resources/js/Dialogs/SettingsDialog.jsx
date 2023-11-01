import {
    Box,
    Checkbox,
    Dialog,
    DialogContent,
    DialogTitle,
    FormControl,
    FormControlLabel,
    Grid, InputLabel, MenuItem, Select,
    Tab,
    Tabs
} from "@mui/material";
import {useSelector} from "react-redux";
import {useEffect, useState} from "react";
import Typography from "@mui/material/Typography";
import {useFormik} from "formik";
import {updateSettings} from "@/Store/Reducers/SettingsReducer.js";
import store from "@/Store/store.js";


function CustomTabPanel(props) {
    const { children, value, index, ...other } = props;

    return (
        <div
            role="tabpanel"
            hidden={value !== index}
            id={`simple-tabpanel-${index}`}
            aria-labelledby={`simple-tab-${index}`}
            {...other}
        >
            {value === index && (
                <Box sx={{ p: 3 }}>
                    {children}
                </Box>
            )}
        </div>
    );
}
const SettingsDialog = (
    {
        open = false,
        onClose = () => {}
    }
) => {

    const settings = useSelector(state => state.settingsReducer);
    const [tab,setTab] = useState(0);


    const formik = useFormik({
        initialValues: settings,
        validateForm: (values) => {

        }

    });

    useEffect(() => {
        store.dispatch(updateSettings(formik.values));
    }, [formik.values]);

    const changeTab = (e,tab) => {
        setTab(tab);
    }
   return <Dialog
        open={open}
        onClose={onClose}
        fullWidth={true}
        maxWidth={'md'}

    >
       <DialogTitle>
           <div
            style={{display: 'flex', justifyContent: 'space-between'}}
           >
               <span style={{width: '100px'}} >{"Ustawienia"}</span>
               <Tabs value={tab} onChange={changeTab} centered >
                   <Tab label="Główna lista" value={0}  />
                   <Tab label="Odcinki" value={1}  />
                   {/*<Tab label="Item Three" value={2} />*/}
               </Tabs>
               <div style={{width: '100px'}} />
           </div>
       </DialogTitle>
       <DialogContent
           sx={{minHeight: '300px'}}
       >
          <CustomTabPanel value={tab} index={0}>
              <Grid container spacing={2}>
                  <Grid item xs={12} md={6}>
                      <FormControlLabel
                          control={<Checkbox
                                checked={formik.values.showOnlyFavorites}
                          />}
                          label="Pokaż tylko ulubione"
                          name="showOnlyFavorites"
                          onChange={formik.handleChange}
                          labelPlacement="end"
                      />
                  </Grid>
                  <Grid item xs={12} md={6}>
                      <FormControl fullWidth>
                          <InputLabel id="sortBy">Sortuj wg.</InputLabel>
                          <Select

                              labelId="orderType"
                              id="sortBy"
                              value={formik.values.sortBy}
                              label="Sortowanie"
                              name={'sortBy'}
                              onChange={formik.handleChange}
                          >
                              <MenuItem value={'name'}>nazwie</MenuItem>
                              <MenuItem value={'complete'}>kompletności</MenuItem>
                              <MenuItem value={'first_release'}>najstarszej premiery</MenuItem>
                              <MenuItem value={'last_release'}>najnowszej premiery</MenuItem>
                          </Select>
                      </FormControl>
                  </Grid>
                  <Grid item xs={12} md={6}>
                      <FormControlLabel
                          control={<Checkbox
                              checked={formik.values.showOnlyNotCompleted}
                          />}
                          label="Pokaż tylko niedokończone"
                          name="showOnlyNotCompleted"
                          onChange={formik.handleChange}
                          labelPlacement="end"
                      />
                  </Grid>
                  <Grid item xs={12} md={6}>
                      <FormControl fullWidth>
                          <InputLabel id="sortDirection">Sortowanie</InputLabel>
                          <Select

                              labelId="orderType"
                              id="sortDirection"
                              value={formik.values.sortDirection}
                              label="Sortowanie"
                              name={'sortDirection'}
                              onChange={formik.handleChange}
                          >
                              <MenuItem value={'asc'}>
                                  {
                                      (formik.values.sortBy === 'name' || formik.values.sortBy === 'complete') ? 'Rosnąco' : 'Od najstarszej do najmłodszej'
                                  }
                              </MenuItem>
                              <MenuItem value={'desc'}>
                                  {
                                      (formik.values.sortBy === 'name' || formik.values.sortBy === 'complete') ? 'Malejąco' : 'Od najmłodszej do najstarszej'
                                  }
                              </MenuItem>
                          </Select>
                      </FormControl>
                  </Grid>
              </Grid>
          </CustomTabPanel>
           <CustomTabPanel value={tab} index={1}>
               <Grid container spacing={2}>
                   <Grid item xs={12} md={6}>
                       <FormControlLabel
                           control={<Checkbox
                               checked={formik.values.hideDownloadedEpisodes}
                           />}
                           label="Ukryj pobrane odcinki"
                           name="hideDownloadedEpisodes"
                           onChange={formik.handleChange}
                           labelPlacement="end"
                       />
                   </Grid>
               </Grid>
           </CustomTabPanel>
           </DialogContent>
    </Dialog>
}

export default SettingsDialog;
