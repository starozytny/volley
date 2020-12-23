import React, { Component } from "react";

import { StyleguideButton }   from "./components/StyleguideButton";
import { StyleguideAlert }    from "./components/StyleguideAlert";
import { StyleguideForm }     from "./components/StyleguideForm";

export class Styleguide extends Component {
    render () {
        return <div className="main-content">
            <StyleguideForm />
            <StyleguideButton />
            <StyleguideAlert />
        </div>
    }
}