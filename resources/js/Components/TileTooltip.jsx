import {Tooltip, tooltipClasses} from "@mui/material";
import {styled} from "@mui/material/styles";

const LightTooltip = styled(({ className, ...props }) => (
    <Tooltip {...props} classes={{ popper: className }} />
))(({ theme }) => ({
    [`& .${tooltipClasses.tooltip}`]: {
        position: 'relative',
        top: '-16px',
        fontSize: '1rem',
    },
}));
const TileTooltip = (
    {
        children,
        name = '',
    }
) => {

    return <LightTooltip  title={name} arrow placement={'bottom'}>
        <div>
            {children}
        </div>
    </LightTooltip>

}
export default TileTooltip;
