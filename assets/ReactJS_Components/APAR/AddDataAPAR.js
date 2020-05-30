class AddDataAPAR extends React.Component{
     render    =    ()   =>   {
          let tP    =    this.props;
     
          return(
               <div className="row mb-3">
                    <div className="col-lg-12">
                         <div className="card">
                              <div className="card-header border-bottom">
                                   <div className="row">
                                        <div className="col-6 text-left">Add Data APAR</div>
                                        <div className="col-6 text-right">
                                             <span className="material-icons cp text-danger"
                                                  onClick={() => tP.back()}>close</span>
                                        </div>
                                   </div>
                              </div>
                              <div className="card-body">
                              </div>
                         </div>
                    </div>
               </div>
          );
              
     }
}