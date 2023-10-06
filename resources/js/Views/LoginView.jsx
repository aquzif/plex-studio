import Container from "@mui/material/Container";
import {Box, Button, FormControlLabel, InputLabel, TextField} from "@mui/material";
import Typography from "@mui/material/Typography";
import styled from "styled-components";
import {useFormik} from "formik";
import store from "@/Store/store.js";
import {login} from "@/Store/Reducers/AuthReducer.js";
import toast from "react-hot-toast";
import AuthAPI from "@/API/AuthAPI.js";
import {useState} from "react";
import LoginFormSchema from "@/Schema/LoginFormSchema.js";

const LoginContainer = styled.div`
  position: absolute;
  background-color: #505050;
  width: 500px;
    height: 400px;
    top: calc(50% - 200px);
    left: calc(50% - 250px);
`;

const LoginView = () => {


    const [isLogining, setIsLogining] = useState(false);

    const tryLogin = async (values) => {
        let result = await AuthAPI.login(values.email,values.password);

        if(result.status === 401) {
            throw new Error('Invalid credentials');
        }

        store.dispatch(login(result.data));

    }

    const formik = useFormik({
        initialValues: {
            email: '',
            password: '',
        },
        validationSchema: LoginFormSchema,
        onSubmit: async values => {
            setIsLogining(true);

            await toast.promise(tryLogin(values), {
                loading: 'Logowanie...',
                success: 'Zalogowano pomyślnie',
                error: 'Błędne dane logowania',
            }).catch((err) => {
                console.log(err);
                setIsLogining(false);
            });


        }
    });

    return (
        <LoginContainer>
            <Container component="main" maxWidth="xs">
                <Box
                    sx={{
                        marginTop: 8,
                        display: "flex",
                        flexDirection: "column",
                        alignItems: "center",
                    }}
                >
                    <Typography component="h1" variant="h5">
                        Sign in
                    </Typography>
                    <Box component="form" onSubmit={formik.handleSubmit} noValidate sx={{ mt: 1 }}>
                        <TextField
                            margin="normal"
                            required
                            fullWidth
                            id="email"
                            label="Email Address"
                            name="email"
                            autoComplete="email"
                            autoFocus
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                            value={formik.values.email}
                        />
                        <InputLabel>{formik.touched.email && formik.errors.email}</InputLabel>
                        <TextField
                            margin="normal"
                            required
                            fullWidth
                            name="password"
                            label="Password"
                            type="password"
                            id="password"
                            autoComplete="current-password"
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                            value={formik.values.password}
                        />
                        <InputLabel>{formik.touched.password && formik.errors.password}</InputLabel>
                        <Button
                            type="submit"
                            fullWidth
                            variant="contained"
                            sx={{ mt: 3, mb: 2 }}
                        >
                            Sign In
                        </Button>
                    </Box>
                </Box>
            </Container>
        </LoginContainer>
    );
}

export default LoginView;
