
import {
    Box, Button,
    Dialog, DialogActions,
    DialogContent,
    DialogTitle,

} from "@mui/material";


const ConfirmDialog = (
    {
        open = false,
        onClose = (result) => {},
        title = 'Czy na pewno?',
    }
) => {


    const closeConfirm = () => {
        onClose(true);
    }

    const closeDeny = () => {
        onClose(false);
    }

    return <Dialog
        open={open}
        onClose={closeDeny}
        fullWidth={true}
        maxWidth={'md'}

    >
        <DialogTitle>
            {title}
        </DialogTitle>
        <DialogContent >
            <DialogActions>
                <Button color={'error'} onClick={closeDeny}>Nie</Button>
                <Button onClick={closeConfirm} autoFocus>
                    Tak
                </Button>
            </DialogActions>
        </DialogContent>

    </Dialog>
}

export default ConfirmDialog;
