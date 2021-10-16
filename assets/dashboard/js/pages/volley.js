import "../../css/pages/volley.scss";

const routes = require('@publicFolder/js/fos_js_routes.json');
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min';

import React from "react";
import { render } from "react-dom";
import { Matchs } from "./components/Volley/Matchs";

Routing.setRoutingData(routes);

let el = document.getElementById("matches");
if(el){
    render(<Matchs {...el.dataset}/>, el)
}