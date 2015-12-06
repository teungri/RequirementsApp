/**
 * Encapsulate table Drag and Drop in a class. We'll have this as a Singleton
 * so we don't get scoping problems.
 */
function TableDnD() {
    /** Keep hold of the current drag object if any */
    this.dragObject = null;
    /** The current mouse offset */
    this.mouseOffset = null;
    /** The current table */
    this.table = null;
    /** Remember the old value of Y so that we don't do too much processing */
    this.oldY = 0;

    /** Initialise the drag and drop by capturing mouse move events */
    this.init = function(table) {
        this.table = table;
        var rows = table.tBodies[0].rows; //getElementsByTagName("tr")
        for (var i=2; i<rows.length; i++) {
            this.makeDraggable(rows[i]);
        }
        var self = this;
        // Now make the onmousemove method in the context of "self" so that we can get back to tableDnD
        document.onmousemove = function(ev){
            if (self.dragObject) {
                ev   = ev || window.event;
                var mousePos = self.mouseCoords(ev);
                var y = mousePos.y - self.mouseOffset.y;
                if (y != self.oldY) {
                    // work out if we're going up or down...
                    var movingDown = y > self.oldY;
                    // update the old value
                    self.oldY = y;
                    // update the style to show we're dragging
                    self.dragObject.style.backgroundColor = "#eee";
                    // If we're over a row then move the dragged row to there so that the user sees the
                    // effect dynamically
                    var currentRow = self.findDropTargetRow(y);
                    if (currentRow) {
                        if (movingDown && self.dragObject != currentRow) {
                            self.dragObject.parentNode.insertBefore(self.dragObject, currentRow.nextSibling);
                        } else if (! movingDown && self.dragObject != currentRow) {
                            self.dragObject.parentNode.insertBefore(self.dragObject, currentRow);
                        }
                    }
                }

                return false;
            }
        };

        // Similarly for the mouseup
        document.onmouseup   = function(ev){
            if (self.dragObject != null) {
                var droppedRow = self.dragObject;
                // If we have a dragObject, then we need to release it,
                // The row will already have been moved to the right place so we just reset stuff
                droppedRow.style.backgroundColor = 'transparent';
                self.dragObject   = null;
                // And then call the onDrop method in case anyone wants to do any post processing
                self.onDrop(self.table, droppedRow);
            }
        };
    }

    /** This function is called when you drop a row, so redefine it in your code
        to do whatever you want, for example use Ajax to update the server */
    this.onDrop = function(table, droppedRow) {
        // Do nothing for now
    }
	/** This function is called when you drop a row, so redefine it in your code
        to do whatever you want, for example use Ajax to update the server */
    this.onDrag = function(table, droppedRow) {
        // Do nothing for now
		alert();
    }

	/** Get the position of an element by going up the DOM tree and adding up all the offsets */
    this.getPosition = function(e){
        var left = 0;
        var top  = 0;
		/** Safari fix -- thanks to Luis Chato for this! */
		if (e.offsetHeight == 0) {
			/** Safari 2 doesn't correctly grab the offsetTop of a table row
			    this is detailed here:
			    http://jacob.peargrove.com/blog/2006/technical/table-row-offsettop-bug-in-safari/
			    the solution is likewise noted there, grab the offset of a table cell in the row - the firstChild.
			    note that firefox will return a text node as a first child, so designing a more thorough
			    solution may need to take that into account, for now this seems to work in firefox, safari, ie */
			e = e.firstChild; // a table cell
		}

        while (e.offsetParent){
            left += e.offsetLeft;
            top  += e.offsetTop;
            e     = e.offsetParent;
        }

        left += e.offsetLeft;
        top  += e.offsetTop;

        return {x:left, y:top};
    }

	/** Get the mouse coordinates from the event (allowing for browser differences) */
    this.mouseCoords = function(ev){
        if(ev.pageX || ev.pageY){
            return {x:ev.pageX, y:ev.pageY};
        }
        return {
            x:ev.clientX + document.body.scrollLeft - document.body.clientLeft,
            y:ev.clientY + document.body.scrollTop  - document.body.clientTop
        };
    }

	/** Given a target element and a mouse event, get the mouse offset from that element.
		To do this we need the element's position and the mouse position */
    this.getMouseOffset = function(target, ev){
        ev = ev || window.event;

        var docPos    = this.getPosition(target);
        var mousePos  = this.mouseCoords(ev);
        return {x:mousePos.x - docPos.x, y:mousePos.y - docPos.y};
    }

	/** Take an item and add an onmousedown method so that we can make it draggable */
    this.makeDraggable = function(item){
        if(!item) return;
        var self = this; // Keep the context of the TableDnd inside the function
        item.onmousedown = function(ev){
            self.dragObject  = this;
            self.mouseOffset = self.getMouseOffset(this, ev);
            return false;
        }
        item.style.cursor = "move";
    }

    /** We're only worried about the y position really, because we can only move rows up and down */
    this.findDropTargetRow = function(y) {
        var rows = this.table.tBodies[0].rows;
        for (var i=2; i<rows.length; i++) {
            var row = rows[i];
            var rowY    = this.getPosition(row).y;
            var rowHeight = parseInt(row.offsetHeight)/2;
			if (row.offsetHeight == 0) {
				rowY = this.getPosition(row.firstChild).y;
				rowHeight = parseInt(row.firstChild.offsetHeight)/2;
			}
            // Because we always have to insert before, we need to offset the height a bit
            if ((y > rowY - rowHeight) && (y < (rowY + rowHeight))) {
                // that's the row we're over
                return row;
            }
        }
        return null;
    }

}
