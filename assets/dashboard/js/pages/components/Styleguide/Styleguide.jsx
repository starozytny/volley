import React, { Component } from "react";

import { StyleguideButton }   from "./components/StyleguideButton";
import { StyleguideAlert }    from "./components/StyleguideAlert";
import { StyleguideForm }     from "./components/StyleguideForm";
import { StyleguideAside }    from "./components/StyleguideAside";
import { StyleguideMaps }     from "./components/StyleguideMaps";

export class Styleguide extends Component {
    render () {
        return <div className="main-content">
            <StyleguideMaps />
            <StyleguideAside />
            <StyleguideForm />
            <StyleguideButton />
            <StyleguideAlert />
        </div>
    }
}