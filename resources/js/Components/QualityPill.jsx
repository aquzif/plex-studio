import styled from "styled-components";

const Pill = styled.div`

  border: 2px solid ${props => props.color};
  color: ${props => props.color};
  text-align: center;
  border-radius: 15px;
  padding: 1px 5px;
  font-weight: bold;

`;

const QualityPill = ({quality = 'undefined'}) => {

    let color = '#878787';
    if(quality === '480p') color = '#008900';
    if(quality === '720p') color = '#00bfff';
    if(quality === '1080p') color = '#8a00ff';
    if(quality === '2160p') color = '#ffbf00';

    if(quality === 'undefined') quality = 'nieznana';
    return <Pill color={color} >{quality}</Pill>

}

export default QualityPill;
