import '../css/app.scss';

const routes = require('@publicFolder/js/fos_js_routes.json');
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min';

import React from 'react';
import { render } from 'react-dom';
import { Menu } from './components/Layout/Menu';
import {Notifications} from "@dashboardComponents/Notifications";

Routing.setRoutingData(routes);

const menu = document.getElementById("menu");
render(
    <Menu {...menu.dataset} />, menu
)

const notifications = document.getElementById("notifications");
if(notifications){
    render(
        <Notifications {...notifications.dataset} />, notifications
    )
}
