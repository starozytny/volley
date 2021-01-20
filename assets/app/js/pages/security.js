import '../../css/pages/security.scss';

import React from 'react';
import { render } from 'react-dom';
import { Forget } from './components/Security/Forget';

const el = document.getElementById("forget");
if(el){
    render(
        <Forget />, el
    )

}
