# Document Notes

TestaViva needs a system to record notes for a document. The Document is discussed over a phone by a lawyer, and during 
that conversation notes will be taken for a given paragraph. The lawyer may then use these notes to remind them what 
actions he has agreed to, ex. a correction or amendment.

#### Backend
In the backend it should be possible to create, read, update but not delete notes.

#### Frontend
A frontend in whatever language you prefer ex. Vue, React, Angular... should provide an interface to show/select/edit 
the legal documents. A method that allows the lawyer to add a comment to a paragraph (footnote or side margin or 
somewhere else ?). 
The frontend should be able to mirror the abilities of the API, i.e. create, read, update, but also the ability to add 
notes to the documents.
With respect to editing the document, then something in the direction of a wysiwyg for some very basic editing would be 
desirable.
We would like to see your frontend coding skills, but using a package to solve a part of the assignment is fully 
acceptable, ex. a wysiwyg package. 

We have provided a 
1) Skeleton installation of Symfony, with the minimum required for routing
2) A few legal (lorem ipsum) documents, presently in html (twig), but we would like to see that moved to a database.

* In addition to above we would like to see some sensible documentation.
* Some verification of the input.
* Responses should be json, with the appropriate headers.
* Some (integration testing or unit) testing.
* Use whichever database technology you are comfortable with.
* The code should be clean.

Please fork the repository, write your code, and push it back up to this repository as a feature branch, using your own name as branch name ex. feature/JensJensen, and make pull request.

Please note that this test should take 3-4 hours, but we will not check the time used from the pushes.
