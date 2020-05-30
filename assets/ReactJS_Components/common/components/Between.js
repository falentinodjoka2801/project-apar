class Between extends React.Component{
     state     =    {
          rentangAwal    :    '',
          rentangAkhir   :    ''
     }
     componentDidMount   =    ()   =>   {
          $('#rentangData').datepicker({});
     }
     render    =    ()   =>   {
          let tP    =    this.props;
          let tS    =    this.state;

          return(
               <div className={'between'}>
                    {tP.label.length >= 1 &&
                         <label className={'d-block'} style={{fontSize:'10.5pt'}}>{tP.label}</label>
                    }
                    <div id={'rentangData'} className="input-daterange input-group my-auto ml-auto mr-auto ml-sm-auto mr-sm-0">
                         <input type="text" className="form-control" name="start" placeholder="Start Date" 
                              id={'rentangData-input-1'} onChange={(e) => {
                                   let rentangAwal     =    e.target.value;
                                   let rentangAkhir    =    tS.rentangAkhir;

                                   this.setState({rentangAwal, rentangAkhir});
                                   tP.componentValue(rentangAwal, rentangAkhir);
                              }} />
                         <input type="text" className="form-control" name="end" placeholder="End Date" 
                              id={'rentangData-input-2'} onChange={(e) => {
                                   let rentangAkhir    =    e.target.value;
                                   let rentangAwal     =    tS.rentangAwal;
                                   
                                   this.setState({rentangAwal, rentangAkhir});
                                   tP.componentValue(rentangAwal, rentangAkhir);
                              }} />
                         {tP.showSearchIcon &&
                              <span className="input-group-append">
                                   <span className="input-group-text">
                                        <i className="material-icons">search</i>
                                   </span>
                              </span>
                         }
                    </div>
               </div>
          );
     }
}

Between.defaultProps      =    {
     label     :    '',
     showSearchIcon :    false
}