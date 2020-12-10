import "../../css/pages/user.scss";

import React from "react";
import { render } from "react-dom";
import { User } from "./components/User/User";

let el = document.getElementById("user");
if(el){
    render(<User />, el)
}
