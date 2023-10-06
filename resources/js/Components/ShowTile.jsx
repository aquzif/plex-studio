import styled from "styled-components";
import {useState} from "react";
import {DeleteForever, Star, StarOutline} from "@mui/icons-material";


const Image = styled.img`
    height: 200px;
    object-fit: cover;
    display: block;
    width: 136px;
`;

const Container = styled.div`
  padding: 10px;
    position: relative;
    width: 156px;
    height: 220px;
`;

const BorderWithInfo = styled.div`
  position: absolute;
    top: 10px;
    left: 10px;
    width: calc(100% - 20px);
    height: calc(100% - 20px);
    border: 4px solid #fff;
`;
const BorderWithInfoRadius = styled.div`
  position: absolute;
    top: 10px;
    left: 10px;
    width: calc(100% - 20px);
    height: calc(100% - 20px);
    border: 4px solid #fff;
    border-radius: 12px;
`;

const CompletedInfo = styled.div`
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 4px 10px;
    border-bottom-left-radius: 10px;
    font-weight: bold;
    font-size: 12px;
`;

const FavoriteButton = styled.div`
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 5px;
    color: yellow;
    cursor: pointer;
`;

const DeleteButton = styled.div`
  position: absolute;
  right: 10px;
  bottom: 10px;
  padding: 5px;
  color: red;
  cursor: pointer;
`;


const ShowTile = (
    {
        name,
        id,
        thumbnail,
        onClick = () => {},
        showBorder = false,
        borderColor= 'red',
        text= '6/10',
        onFavoriteClick = null,
        isFavorite = false,
        onDeleteClick = null
    }
) => {

    const [hovered, setHovered] = useState(false);


    const handleClick = (e) => {
        if(e.target.id.includes('hrefTarget'))
            onClick(id);
    }

    return (
        <Container onClick={handleClick} onMouseLeave={() => setHovered(false)} onMouseEnter={() => setHovered(true)}  >
            <Image id={'hrefTarget-4'} src={thumbnail} alt={name}/>
            {
                showBorder && (<>
                    <BorderWithInfo id={'hrefTarget-1'} style={{borderColor: borderColor}} />
                    <BorderWithInfoRadius id={'hrefTarget-2'} style={{borderColor: borderColor}} />
                    <CompletedInfo id={'hrefTarget-3'} style={{backgroundColor: borderColor}} >
                        {text}
                    </CompletedInfo>
                    {
                        onDeleteClick && hovered && <>
                            <DeleteButton
                                onClick={() => onDeleteClick(id)}
                                >
                                <DeleteForever />
                            </DeleteButton>
                        </>
                    }
                    {
                        onFavoriteClick && <>
                            <FavoriteButton
                                onClick={() => onFavoriteClick(id)}
                            >
                                {
                                    !isFavorite && hovered && <StarOutline/>
                                }
                                {
                                    isFavorite  && <Star/>
                                }
                            </FavoriteButton>
                        </>
                    }
                </>)
            }
        </Container>
    )
}

export default ShowTile;
