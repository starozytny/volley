import '../../css/pages/security.scss';

const routes = require('@publicFolder/js/fos_js_routes.json');
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min';

import React from 'react';
import { render } from 'react-dom';
import { Forget } from './components/Security/Forget';

Routing.setRoutingData(routes);

const el = document.getElementById("forget");
if(el){
    render(
        <Forget />, el
    )

}
